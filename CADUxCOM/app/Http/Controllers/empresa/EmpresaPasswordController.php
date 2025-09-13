<?php

namespace App\Http\Controllers\empresa; // Corregido: namespace con 'empresa'

use App\Http\Controllers\Controller; // Asegúrate de importar el controlador base
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EmpresaPasswordController extends Controller
{
    /**
     * Muestra el formulario para cambiar la contraseña de la empresa.
     *
     * @return \Illuminate\View\View
     */
    public function showChangeForm()
    {
        // Asegura que solo una empresa autenticada pueda ver esta página
        if (!Auth::guard('empresa')->check()) {
            return redirect()->route('empresa.login')->with('error', 'Debes iniciar sesión para cambiar tu contraseña.');
        }

        return view('empresa.password.change');
    }

    /**
     * Actualiza la contraseña de la empresa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function updatePassword(Request $request)
    {
        try {
            // Obtén la empresa autenticada
            $empresa = Auth::guard('empresa')->user();

            // Valida los campos de la contraseña
            $request->validate([
                'current_password' => ['required', 'string'],
                'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            ], [
                'current_password.required' => 'La contraseña actual es obligatoria.',
                'new_password.required' => 'La nueva contraseña es obligatoria.',
                'new_password.min' => 'La nueva contraseña debe tener al menos :min caracteres.',
                'new_password.confirmed' => 'La confirmación de la nueva contraseña no coincide.',
            ]);

            // Verifica si la contraseña actual es correcta
            if (!Hash::check($request->current_password, $empresa->password)) {
                throw ValidationException::withMessages([
                    'current_password' => 'La contraseña actual no es correcta.',
                ]);
            }

            // Actualiza la contraseña en la base de datos
            $empresa->password = Hash::make($request->new_password);
            $empresa->save();

            // Responde con JSON para una mejor experiencia de usuario (similar a tu perfil)
            return response()->json(['success' => true, 'message' => 'Contraseña actualizada correctamente ✅.']);

        } catch (ValidationException $e) {
            // Maneja errores de validación
            return response()->json(['success' => false, 'message' => 'Error de validación.', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            // Maneja cualquier otro error
            return response()->json(['success' => false, 'message' => 'Hubo un problema al actualizar la contraseña ❌. ' . $e->getMessage()], 500);
        }
    }
}