<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Empresa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmpresaEmailVerificationController extends Controller
{
    /**
     * Verifica el correo de la empresa usando una URL firmada.
     */
    public function verify(Request $request, int $id, string $hash): RedirectResponse
    {
        $empresa = Empresa::findOrFail($id);

        // Validar hash de correo
        if (! hash_equals($hash, sha1($empresa->email))) {
            return redirect()->route('login')->withErrors(['email' => 'El enlace de verificación no es válido o ha expirado.']);
        }

        if (! $empresa->email_verified_at) {
            $empresa->forceFill([
                'email_verified_at' => now(),
            ])->save();

            // Crear notificación in-app al verificar correo (habilitar sandbox)
            Notification::create([
                'type' => 'account_status',
                'title' => 'Correo verificado — Modo Sandbox habilitado',
                'message' => 'Tu empresa puede usar el panel en modo Sandbox mientras validamos tus datos.',
                'data' => ['status' => 'sandbox'],
                'empresa_id' => $empresa->Id_Empresa,
                'priority' => 'low',
                'channel' => 'in_app',
            ]);
        }

        return redirect()->route('login')->with('success', 'Correo verificado exitosamente. Tu empresa está en modo Sandbox: ya puedes iniciar sesión y usar el panel mientras validamos tus datos.');
    }
}