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
     * Mostrar el formulario de perfil del usuario.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualizar la información del perfil del usuario (con soporte para foto, AJAX y campos extra).
     */
    public function update(ProfileUpdateRequest $request)
    {
        try {
            \Log::info('Iniciando actualización de perfil', [
                'has_file' => $request->hasFile('foto'),
                'is_ajax' => $request->ajax(),
                'data' => $request->all()
            ]);

            $user = $request->user();

            // Actualizar campos validados del formulario
            $user->fill($request->validated());

            // Actualizar campos adicionales personalizados
            $user->apellido = $request->input('apellido', $user->apellido);
            $user->contacto = $request->input('contacto', $user->contacto);
            $user->ubicacion = $request->input('ubicacion', $user->ubicacion);

            // Manejar subida de foto de perfil
            if ($request->hasFile('foto')) {
                $file = $request->file('foto');

                if (!$file->isValid()) {
                    throw new \Exception('El archivo de la foto no es válido.');
                }

                $filename = time() . '_' . $file->getClientOriginalName();
                $destinationPath = storage_path('app/public/photos');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }

                // Guardar la nueva imagen
                $file->move($destinationPath, $filename);
                $user->foto = 'photos/' . $filename;

                \Log::info('Foto de perfil actualizada', ['path' => $user->foto]);
            }

            // Si el email cambió, invalidar la verificación anterior
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            $user->save();
            \Log::info('Perfil actualizado correctamente', ['user_id' => $user->id]);

            // Si es una petición AJAX o con foto, devolver JSON
            if ($request->ajax() || $request->hasFile('foto')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Perfil actualizado correctamente.',
                    'user' => [
                        'name' => $user->name,
                        'apellido' => $user->apellido,
                        'email' => $user->email,
                        'contacto' => $user->contacto,
                        'ubicacion' => $user->ubicacion,
                        'foto' => $user->foto ? asset('storage/' . $user->foto) : null,
                    ],
                ]);
            }

            // Si es una petición normal
            return Redirect::route('profile.edit')->with('status', 'Perfil actualizado correctamente.');

        } catch (\Exception $e) {
            \Log::error('Error al actualizar perfil', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($request->ajax() || $request->hasFile('foto')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar el perfil: ' . $e->getMessage(),
                ], 500);
            }

            return Redirect::route('profile.edit')->with('error', 'Error al actualizar el perfil: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar la cuenta del usuario.
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
