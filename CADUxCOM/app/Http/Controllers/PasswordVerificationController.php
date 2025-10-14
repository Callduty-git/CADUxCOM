<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\PasswordResetToken;
use App\Mail\PasswordChangeVerificationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordVerificationController extends Controller
{
    /**
     * Mostrar formulario para solicitar cambio de contraseña
     */
    public function showRequestForm()
    {
        return view('auth.password-change-request');
    }

    /**
     * Enviar email de verificación para cambio de contraseña
     */
    public function sendVerificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.exists' => 'No existe una cuenta con este correo electrónico.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $user = User::where('email', $request->email)->first();
            
            // Generar token de verificación
            $tokenRecord = PasswordResetToken::generateToken($user->email, 'password_reset', 1);
            
            // Enviar email de verificación
            Mail::to($user->email)->send(new PasswordChangeVerificationMail($user, $tokenRecord->token));
            
            return back()->with('success', 'Se ha enviado un enlace de verificación a tu correo electrónico. Revisa tu bandeja de entrada y sigue las instrucciones.');
            
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de verificación: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al enviar el email de verificación. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Mostrar formulario de verificación con token
     */
    public function showVerificationForm(Request $request)
    {
        $email = $request->query('email');
        $token = $request->query('token');

        if (!$email || !$token) {
            return redirect()->route('password.request')->with('error', 'Enlace de verificación inválido.');
        }

        // Verificar que el token sea válido
        if (!PasswordResetToken::isValidToken($email, $token)) {
            return redirect()->route('password.request')->with('error', 'El enlace de verificación ha expirado o ya ha sido usado.');
        }

        return view('auth.password-change-form', compact('email', 'token'));
    }

    /**
     * Procesar el cambio de contraseña después de la verificación
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'token' => 'required|string',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.exists' => 'No existe una cuenta con este correo electrónico.',
            'token.required' => 'El token de verificación es obligatorio.',
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Verificar nuevamente que el token sea válido
            if (!PasswordResetToken::isValidToken($request->email, $request->token)) {
                return back()->with('error', 'El token de verificación ha expirado o ya ha sido usado.');
            }

            // Buscar el usuario
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return back()->with('error', 'Usuario no encontrado.');
            }

            // Actualizar la contraseña
            $user->password = Hash::make($request->password);
            $user->save();

            // Marcar el token como usado
            PasswordResetToken::markAsUsed($request->email, $request->token);

            // Limpiar tokens expirados
            PasswordResetToken::cleanExpiredTokens();

            // Si el usuario está logueado, cerrar sesión para que se vuelva a autenticar
            if (Auth::check() && Auth::user()->email === $request->email) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                return redirect()->route('login')->with('success', 'Tu contraseña ha sido cambiada exitosamente. Por favor, inicia sesión con tu nueva contraseña.');
            }

            return redirect()->route('login')->with('success', 'Tu contraseña ha sido cambiada exitosamente. Por favor, inicia sesión con tu nueva contraseña.');
            
        } catch (\Exception $e) {
            \Log::error('Error al cambiar contraseña: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al cambiar la contraseña. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Mostrar formulario de cambio de contraseña desde el perfil (con verificación por email)
     */
    public function showProfilePasswordForm()
    {
        $user = Auth::user();
        return view('profile.password-change-verification', compact('user'));
    }

    /**
     * Enviar email de verificación desde el perfil
     */
    public function sendProfileVerificationEmail(Request $request)
    {
        $user = Auth::user();
        
        try {
            // Generar token de verificación
            $tokenRecord = PasswordResetToken::generateToken($user->email, 'password_reset', 1);
            
            // Enviar email de verificación
            Mail::to($user->email)->send(new PasswordChangeVerificationMail($user, $tokenRecord->token));
            
            return back()->with('success', 'Se ha enviado un enlace de verificación a tu correo electrónico (' . $user->email . '). Revisa tu bandeja de entrada y sigue las instrucciones.');
            
        } catch (\Exception $e) {
            \Log::error('Error al enviar email de verificación desde perfil: ' . $e->getMessage());
            return back()->with('error', 'Hubo un error al enviar el email de verificación. Por favor, inténtalo de nuevo.');
        }
    }
}