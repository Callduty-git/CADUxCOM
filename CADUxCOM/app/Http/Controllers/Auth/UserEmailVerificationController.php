<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\UserEmailVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class UserEmailVerificationController extends Controller
{
    /**
     * Verificar email del usuario
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        // Verificar que el hash coincida
        if (!hash_equals((string) $hash, sha1($user->email))) {
            return redirect()->route('login')
                ->with('error', 'El enlace de verificación no es válido.');
        }

        // Verificar que el enlace no haya expirado
        if (!URL::hasValidSignature($request)) {
            return redirect()->route('login')
                ->with('error', 'El enlace de verificación ha expirado. Por favor, solicita uno nuevo.');
        }

        // Marcar el email como verificado
        $user->update([
            'email_verified' => true,
            'email_verified_at' => now(),
        ]);

        // Hacer login automático del usuario
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('success', '¡Email verificado exitosamente! Bienvenido a CADUxCOM.');
    }

    /**
     * Reenviar email de verificación
     */
    public function resend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->email_verified) {
            return back()->with('info', 'Este email ya ha sido verificado.');
        }

        // Reenviar email de verificación
        Mail::to($user->email)->send(new UserEmailVerification($user));

        return back()->with('success', 'Se ha reenviado el email de verificación.');
    }
}