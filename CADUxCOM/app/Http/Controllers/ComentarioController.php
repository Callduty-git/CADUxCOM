<?php

namespace App\Http\Controllers;

use App\Models\Comentario;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComentarioController extends Controller
{
    /**
     * Mostrar comentarios de un producto
     */
    public function show(Request $request, $productoId): JsonResponse
    {
        try {
            $producto = Producto::findOrFail($productoId);
            
            $comentarios = $producto->comentariosPrincipales()->get();
            
            // Obtener permisos del usuario actual
            $permissions = $this->getUserPermissions();
            
            return response()->json([
                'success' => true,
                'comentarios' => $comentarios,
                'permissions' => $permissions,
                'producto' => [
                    'id' => $producto->Id_Producto,
                    'nombre' => $producto->Nombre,
                    'empresa_id' => $producto->Id_Empresa,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cargar comentarios'
            ], 500);
        }
    }

    /**
     * Guardar un nuevo comentario o respuesta
     */
    public function store(Request $request): JsonResponse
    {
        // Validar que el usuario esté autenticado
        if (!Auth::check() && !Auth::guard('empresa')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Debes iniciar sesión para comentar'
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'contenido' => 'required|string|min:3|max:1000',
            'producto_id' => 'required|exists:productos,Id_Producto',
            'parent_id' => 'nullable|exists:comentarios,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $validator->errors()
            ], 422);
        }

        $producto = Producto::findOrFail($request->producto_id);

        // Verificar permisos para responder
        if ($request->parent_id) {
            $parentComment = Comentario::findOrFail($request->parent_id);
            
            // Solo las empresas pueden responder a comentarios de usuarios sobre sus productos
            if (Auth::guard('empresa')->check()) {
                $empresa = Auth::guard('empresa')->user();
                if ($producto->Id_Empresa !== $empresa->Id_Empresa) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Solo puedes responder a comentarios de tus productos'
                    ], 403);
                }
            } else {
                // Los usuarios comunes no pueden responder (solo comentar)
                return response()->json([
                    'success' => false,
                    'message' => 'Los usuarios no pueden responder comentarios'
                ], 403);
            }
        }

        $comentario = new Comentario();
        $comentario->contenido = $request->contenido;
        $comentario->producto_id = $request->producto_id;
        $comentario->parent_id = $request->parent_id;

        // Asignar autor según el tipo de usuario autenticado
        if (Auth::guard('empresa')->check()) {
            $comentario->empresa_id = Auth::guard('empresa')->user()->Id_Empresa;
        } else {
            $comentario->user_id = Auth::user()->id;
        }

        $comentario->save();
        $comentario->load(['user', 'empresa', 'replies']);

        return response()->json([
            'success' => true,
            'message' => $request->parent_id ? 'Respuesta publicada' : 'Comentario publicado',
            'comentario' => $comentario
        ]);
    }

    /**
     * Actualizar un comentario existente
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $comentario = Comentario::findOrFail($id);
            
            // Verificar permisos de edición
            if (!$this->canEditComment($comentario)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para editar este comentario'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'contenido' => 'required|string|min:3|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Datos inválidos',
                    'errors' => $validator->errors()
                ], 422);
            }

            $comentario->contenido = $request->contenido;
            $comentario->save();
            $comentario->load(['user', 'empresa', 'replies']);

            return response()->json([
                'success' => true,
                'message' => 'Comentario actualizado correctamente',
                'comentario' => $comentario
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar comentario'
            ], 500);
        }
    }

    /**
     * Eliminar un comentario
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $comentario = Comentario::findOrFail($id);
            
            // Verificar permisos de eliminación
            if (!$this->canDeleteComment($comentario)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No tienes permisos para eliminar este comentario'
                ], 403);
            }

            // Si es administrador, puede eliminar comentarios principales con todas sus respuestas
            if (Auth::check() && Auth::user()->role === 'admin' && $comentario->isMainComment()) {
                $comentario->replies()->delete();
            }

            $comentario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Comentario eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar comentario'
            ], 500);
        }
    }

    /**
     * Verificar permisos del usuario actual
     */
    private function getUserPermissions(): array
    {
        $permissions = [
            'can_comment' => false,
            'can_reply' => false,
            'can_delete' => false,
            'user_type' => 'guest',
            'user_id' => null,
            'is_admin' => false,
        ];

        if (Auth::check()) {
            $user = Auth::user();
            $permissions['can_comment'] = true;
            $permissions['can_delete'] = $user->role === 'admin';
            $permissions['user_type'] = $user->role === 'admin' ? 'admin' : 'user';
            $permissions['user_id'] = $user->id;
            $permissions['is_admin'] = $user->role === 'admin';
        } elseif (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            $permissions['can_comment'] = true;
            $permissions['can_reply'] = true;
            $permissions['user_type'] = 'empresa';
            $permissions['user_id'] = $empresa->Id_Empresa;
        }

        return $permissions;
    }

    /**
     * Verificar si el usuario puede editar un comentario específico
     */
    private function canEditComment(Comentario $comentario): bool
    {
        // Administradores pueden editar cualquier comentario
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }

        // Usuarios pueden editar sus propios comentarios
        if (Auth::check() && $comentario->user_id === Auth::user()->id) {
            return true;
        }

        // Empresas pueden editar sus propias respuestas
        if (Auth::guard('empresa')->check() && $comentario->empresa_id === Auth::guard('empresa')->user()->Id_Empresa) {
            return true;
        }

        return false;
    }

    /**
     * Verificar si el usuario puede eliminar un comentario específico
     */
    private function canDeleteComment(Comentario $comentario): bool
    {
        // Administradores pueden eliminar cualquier comentario
        if (Auth::check() && Auth::user()->role === 'admin') {
            return true;
        }

        // Usuarios pueden eliminar sus propios comentarios
        if (Auth::check() && $comentario->user_id === Auth::user()->id) {
            return true;
        }

        // Empresas pueden eliminar solo sus propias respuestas
        if (Auth::guard('empresa')->check() && $comentario->empresa_id === Auth::guard('empresa')->user()->Id_Empresa) {
            return $comentario->isReply(); // Solo respuestas, no comentarios principales
        }

        return false;
    }

    /**
     * Verificar si el usuario puede responder a un comentario específico
     */
    private function canReplyToComment(Comentario $comentario, Producto $producto): bool
    {
        // Solo las empresas pueden responder
        if (!Auth::guard('empresa')->check()) {
            return false;
        }

        $empresa = Auth::guard('empresa')->user();
        
        // Solo pueden responder a comentarios de sus productos
        return $producto->Id_Empresa === $empresa->Id_Empresa;
    }
}
