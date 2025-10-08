<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            \Log::info('Iniciando actualización de perfil', [
                'has_file' => $request->hasFile('foto'),
                'is_ajax' => $request->ajax(),
                'all_data' => $request->all()
            ]);
            
            $user = $request->user();
            
            // Actualizar campos básicos
            $user->name = $request->name;
            $user->email = $request->email;
            
            // Actualizar campos adicionales si existen
            if ($request->has('apellido')) {
                $user->apellido = $request->apellido;
            }
            if ($request->has('contacto')) {
                $user->contacto = $request->contacto;
            }
            if ($request->has('ubicacion')) {
                $user->ubicacion = $request->ubicacion;
            }
            
            // Manejar subida de foto
            if ($request->hasFile('foto')) {
                \Log::info('Procesando archivo de foto');
                $file = $request->file('foto');
                
                \Log::info('Datos del archivo', [
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'is_valid' => $file->isValid()
                ]);
                
                // Validar el archivo
                if (!$file->isValid()) {
                    \Log::error('Archivo no válido');
                    throw new \Exception('El archivo no es válido');
                }
                
                $filename = time() . '_' . $file->getClientOriginalName();
                \Log::info('Intentando guardar archivo', ['filename' => $filename]);
                
                // Usar move en lugar de storeAs
                $destinationPath = storage_path('app/public/photos');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                
                $file->move($destinationPath, $filename);
                $path = 'public/photos/' . $filename;
                
                \Log::info('Archivo guardado', ['path' => $path, 'destination' => $destinationPath]);
                
                if (!$path) {
                    throw new \Exception('Error al guardar el archivo');
                }
                
                $user->foto = 'photos/' . $filename;
                \Log::info('Foto asignada al usuario', ['foto' => $user->foto]);
            }

            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
            \Log::info('Usuario guardado exitosamente');

            // Si es una petición AJAX, devolver JSON
            if ($request->ajax() || $request->hasFile('foto')) {
                $fotoUrl = null;
                if ($user->foto) {
                    $fotoUrl = asset('storage/' . $user->foto);
                    \Log::info('URL de la foto generada', ['url' => $fotoUrl, 'foto_path' => $user->foto]);
                }
                
                $response = [
                    'success' => true,
                    'message' => 'Perfil actualizado correctamente',
                    'user' => [
                        'name' => $user->name,
                        'apellido' => $user->apellido,
                        'email' => $user->email,
                        'contacto' => $user->contacto,
                        'ubicacion' => $user->ubicacion,
                        'foto' => $fotoUrl
                    ]
                ];
                
                \Log::info('Enviando respuesta JSON', $response);
                return response()->json($response);
            }

            return Redirect::route('profile.edit')->with('status', 'Perfil actualizado correctamente');
            
        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Si es una petición AJAX, devolver error JSON
            if ($request->ajax() || $request->hasFile('foto')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil: ' . $e->getMessage()
                ], 500);
            }
            
            return Redirect::route('profile.edit')->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
