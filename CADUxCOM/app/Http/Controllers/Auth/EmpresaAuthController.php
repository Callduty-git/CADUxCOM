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
        // Validar las credenciales
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intentar autenticación con el guard empresa
        if (Auth::guard('empresa')->attempt($credentials, $request->filled('remember'))) {
            $empresa = Auth::guard('empresa')->user();

            // Verificar estado de la empresa
            if ($empresa->status !== 'approved') {
                Auth::guard('empresa')->logout();

                $message = match($empresa->status) {
                    'pending' => 'Tu cuenta está pendiente de aprobación. Recibirás una notificación por correo electrónico una vez que se complete la verificación.',
                    'rejected' => 'Tu cuenta ha sido rechazada. Contacta al administrador para más información.',
                    default => 'Tu cuenta no está disponible en este momento.',
                };

                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            // Regenerar la sesión para seguridad
            $request->session()->regenerate();

            // Redirigir al panel de productos o dashboard de la empresa
            return redirect()->route('empresa.dashboard');
        }

        // Si falla, volver con error
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con una empresa registrada.',
        ])->onlyInput('email');
    }

    /**
     * Cierra la sesión de una empresa.
     */
    public function logout(Request $request)
    {
        Auth::guard('empresa')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('empresa.login');
    }
}
