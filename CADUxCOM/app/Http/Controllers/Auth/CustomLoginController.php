<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CustomLoginController extends Controller
{
    /**
     * Mostrar el formulario de login unificado.
     * Si se pasa un parámetro "redirect", se guarda para redirigir luego.
     */
    public function showLoginForm(Request $request)
    {
        // Guardar la URL de redirección si viene como parámetro
        if ($request->has('redirect')) {
            session(['url.intended' => $request->get('redirect')]);
        }

        return view('auth.login'); // Vista unificada para login
    }

    /**
     * Procesar el intento de login tanto para usuarios como para empresas.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        /**
         * Intentar login como usuario común
         */
        if (Auth::guard('web')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $user = Auth::guard('web')->user();

            $request->session()->regenerate();

            // Redirigir usuarios a home.blade
            $intended = session('url.intended');
            if ($intended && $this->isSafeRedirect($intended)) {
                return redirect()->intended(route('home'));
            }

            return redirect()->route('home');
        }

        /**
         * Intentar login como empresa
         */
        if (Auth::guard('empresa')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $empresa = Auth::guard('empresa')->user();

            // Validar estado de la empresa (approved, sandbox, pending, rejected, etc.)
            if (!in_array($empresa->status, ['approved', 'sandbox'])) {
                Auth::guard('empresa')->logout();

                $message = match ($empresa->status) {
                    'pending' => 'Tu cuenta está pendiente de aprobación. Recibirás una notificación una vez completada la verificación.',
                    'rejected' => 'Tu cuenta ha sido rechazada. Contacta al administrador para más información.',
                    'suspended' => 'Tu cuenta ha sido suspendida temporalmente. Contacta al soporte.',
                    default => 'Tu cuenta no está disponible en este momento.',
                };

                return back()->withErrors([
                    'email' => $message,
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            // Redirigir empresas a dashboard.blade
            $intended = session('url.intended');
            if ($intended && $this->isSafeRedirect($intended)) {
                return redirect()->intended(route('empresa.dashboard'));
            }

            return redirect()->route('empresa.dashboard');
        }

        /**
         * Si ambos intentos fallan
         */
        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión para usuario o empresa.
     */
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

    /**
     * Determina si la URL de redirección es segura (no endpoints JSON o API).
     */
    private function isSafeRedirect(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        $blocked = [
            '/wishlist/count',
            '/wishlist/status',
            '/wishlist/multiple-status',
            '/cart/count',
            '/cart/add',
            '/cart/update',
            '/cart/remove',
            '/cart/clear',
        ];

        foreach ($blocked as $segment) {
            if (Str::endsWith($path, $segment)) {
                return false;
            }
        }

        return true;
    }
}
