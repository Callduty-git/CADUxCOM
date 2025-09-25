<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CustomLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); // Vista unificada para login
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar login como usuario
        if (Auth::guard('web')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $user = Auth::guard('web')->user();
            
            // Verificar si el email está verificado
            if (!$user->email_verified) {
                Auth::guard('web')->logout();
                return back()->withErrors([
                    'email' => 'Debes verificar tu email antes de poder iniciar sesión. Revisa tu correo electrónico.',
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('home'));
        }

        // Intentar login como empresa
        if (Auth::guard('empresa')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $empresa = Auth::guard('empresa')->user();
            
            // Verificar si la empresa está aprobada
            if ($empresa->status !== 'approved') {
                Auth::guard('empresa')->logout();
                
                $message = match($empresa->status) {
                    'pending' => 'Tu cuenta está pendiente de aprobación. Recibirás una notificación por correo electrónico una vez que se complete la verificación.',
                    'rejected' => 'Tu cuenta ha sido rechazada. Contacta al administrador para más información.',
                    default => 'Tu cuenta no está disponible en este momento.'
                };
                
                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }
            
            $request->session()->regenerate();
            return redirect()->intended(route('empresa.dashboard'));
        }

        // Si ambos fallan
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('empresa')->check()) {
            Auth::guard('empresa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
