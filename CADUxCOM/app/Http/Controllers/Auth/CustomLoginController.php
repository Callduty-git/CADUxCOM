<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Str;

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

        /**
         * Intentar login como usuario normal
         */
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

            // Validar redirección segura
            $intended = session('url.intended');
            if ($intended && $this->isSafeRedirect($intended)) {
                return redirect()->intended(route('dashboard'));
            }

            return redirect()->route('dashboard');
        }

        /**
         * Intentar login como empresa
         */
        if (Auth::guard('empresa')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $empresa = Auth::guard('empresa')->user();

            // Validar estado de la empresa
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

            $request->session()->regenerate();

            // Validar redirección segura
            $intended = session('url.intended');
            if ($intended && $this->isSafeRedirect($intended)) {
                return redirect()->intended(route('empresa.dashboard'));
            }

            return redirect()->route('empresa.dashboard');
        }

        /**
         * Si ambos fallan
         */
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

    /**
     * Determina si la URL intended es segura para redirigir (no endpoints JSON/API).
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
