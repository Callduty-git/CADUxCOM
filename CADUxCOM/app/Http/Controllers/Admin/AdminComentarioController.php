<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comentario;
use App\Models\Producto;
use App\Models\User;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminComentarioController extends Controller
{
    /**
     * Mostrar todas las reseñas con filtros
     */
    public function index(Request $request)
    {
        $query = Comentario::with(['user', 'empresa', 'producto'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contenido', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('empresa', function($empresaQuery) use ($search) {
                      $empresaQuery->where('Nombre', 'like', "%{$search}%");
                  })
                  ->orWhereHas('producto', function($productoQuery) use ($search) {
                      $productoQuery->where('Nombre', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('tipo')) {
            if ($request->tipo === 'usuario') {
                $query->whereNotNull('user_id');
            } elseif ($request->tipo === 'empresa') {
                $query->whereNotNull('empresa_id');
            }
        }

        if ($request->filled('fecha_desde')) {
            $query->whereDate('created_at', '>=', $request->fecha_desde);
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('created_at', '<=', $request->fecha_hasta);
        }

        $comentarios = $query->paginate(20);

        // Estadísticas
        $stats = [
            'total' => Comentario::count(),
            'usuarios' => Comentario::whereNotNull('user_id')->count(),
            'empresas' => Comentario::whereNotNull('empresa_id')->count(),
            'hoy' => Comentario::whereDate('created_at', today())->count(),
            'esta_semana' => Comentario::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
        ];

        // Obtener todas las empresas para el filtro
        $empresas = Empresa::select('Id_Empresa as id', 'Nombre as nombre')->orderBy('Nombre')->get();

        return view('admin.comentarios.index', compact('comentarios', 'stats', 'empresas'));
    }

    /**
     * Mostrar detalles de una reseña específica
     */
    public function show($id)
    {
        $comentario = Comentario::with(['user', 'empresa', 'producto', 'respuestas.user', 'respuestas.empresa'])
            ->findOrFail($id);

        return view('admin.comentarios.show', compact('comentario'));
    }

    /**
     * Eliminar una reseña
     */
    public function destroy($id): JsonResponse
    {
        try {
            $comentario = Comentario::findOrFail($id);
            
            // Eliminar también las respuestas
            $comentario->respuestas()->delete();
            $comentario->delete();

            return response()->json([
                'success' => true,
                'message' => 'Reseña eliminada correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la reseña'
            ], 500);
        }
    }

    /**
     * Eliminar múltiples reseñas
     */
    public function destroyMultiple(Request $request): JsonResponse
    {
        try {
            $ids = $request->input('ids', []);
            
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se seleccionaron reseñas para eliminar'
                ], 400);
            }

            // Eliminar respuestas primero
            Comentario::whereIn('parent_id', $ids)->delete();
            
            // Eliminar comentarios principales
            $deleted = Comentario::whereIn('id', $ids)->delete();

            return response()->json([
                'success' => true,
                'message' => "Se eliminaron {$deleted} reseñas correctamente"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar las reseñas'
            ], 500);
        }
    }

    /**
     * Obtener estadísticas para el dashboard
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total_comentarios' => Comentario::count(),
                'comentarios_hoy' => Comentario::whereDate('created_at', today())->count(),
                'comentarios_semana' => Comentario::whereBetween('created_at', [
                    now()->startOfWeek(), 
                    now()->endOfWeek()
                ])->count(),
                'comentarios_mes' => Comentario::whereBetween('created_at', [
                    now()->startOfMonth(), 
                    now()->endOfMonth()
                ])->count(),
                'productos_con_comentarios' => Comentario::distinct('producto_id')->count(),
                'usuarios_activos' => Comentario::whereNotNull('user_id')->distinct('user_id')->count(),
                'empresas_activas' => Comentario::whereNotNull('empresa_id')->distinct('empresa_id')->count(),
            ];

            return response()->json([
                'success' => true,
                'stats' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener estadísticas'
            ], 500);
        }
    }

    /**
     * Exportar reseñas a CSV
     */
    public function export(Request $request)
    {
        $query = Comentario::with(['user', 'empresa', 'producto']);

        // Aplicar los mismos filtros que en index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contenido', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('empresa', function($empresaQuery) use ($search) {
                      $empresaQuery->where('Nombre', 'like', "%{$search}%");
                  });
            });
        }

        $comentarios = $query->get();

        $filename = 'reseñas_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($comentarios) {
            $file = fopen('php://output', 'w');
            
            // Encabezados CSV
            fputcsv($file, [
                'ID',
                'Contenido',
                'Autor',
                'Tipo Autor',
                'Producto',
                'Fecha Creación',
                'Respuestas'
            ]);

            // Datos
            foreach ($comentarios as $comentario) {
                $autor = $comentario->user ? $comentario->user->name : ($comentario->empresa ? $comentario->empresa->Nombre : 'Desconocido');
                $tipoAutor = $comentario->user ? 'Usuario' : ($comentario->empresa ? 'Empresa' : 'Desconocido');
                $producto = $comentario->producto ? $comentario->producto->Nombre : 'Producto eliminado';
                $respuestas = $comentario->respuestas()->count();

                fputcsv($file, [
                    $comentario->id,
                    $comentario->contenido,
                    $autor,
                    $tipoAutor,
                    $producto,
                    $comentario->created_at->format('Y-m-d H:i:s'),
                    $respuestas
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}