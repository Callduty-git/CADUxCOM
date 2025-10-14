<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:empresa')->except('logout');
    }

    /**
     * Muestra el formulario de login para empresas.
     */
    public function showLoginForm()
    {
        return view('empresa.auth.login');
    }

    /**
     * Procesa el inicio de sesión de una empresa.
     */
    public function login(Request $request)
    {
        // Validar las credenciales ingresadas
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticación con el guard empresa
        if (Auth::guard('empresa')->attempt($credentials, $request->filled('remember'))) {
            $empresa = Auth::guard('empresa')->user();

            /**
             * ✅ Verificar si el correo electrónico de la empresa está verificado
             */
            if (is_null($empresa->email_verified_at)) {
                Auth::guard('empresa')->logout();

                return back()->withErrors([
                    'email' => 'Debes verificar tu correo electrónico antes de poder iniciar sesión. Revisa tu bandeja de entrada.',
                ])->onlyInput('email');
            }

            /**
             * ✅ Verificar estado de la empresa
             */
            if (!in_array($empresa->status, ['approved', 'sandbox'])) {
                Auth::guard('empresa')->logout();

                $message = match ($empresa->status) {
                    'pending' => 'Tu cuenta está pendiente de aprobación. Recibirás una notificación por correo electrónico una vez que se complete la verificación.',
                    'rejected' => 'Tu cuenta ha sido rechazada. Contacta al administrador para más información.',
                    'sandbox' => 'Tu cuenta está en modo Sandbox, pero temporalmente no disponible.',
                    'suspended' => 'Tu cuenta ha sido suspendida temporalmente. Contacta al soporte.',
                    default => 'Tu cuenta no está disponible en este momento.',
                };

                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            /**
             * ✅ Regenerar sesión por seguridad
             */
            $request->session()->regenerate();

            /**
             * ✅ Redirigir al dashboard de empresa
             */
            return redirect()->route('empresa.dashboard');
        }

        /**
         * ❌ Si la autenticación falla
         */
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con una empresa registrada.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión de una empresa.
     */
    public function logout(Request $request)
    {
        if (Auth::guard('empresa')->check()) {
            Auth::guard('empresa')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('empresa.login');
    }
}
