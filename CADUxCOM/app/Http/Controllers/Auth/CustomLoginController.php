<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Empresa;
use Illuminate\Support\Facades\Hash;
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

        // Intentar login como usuario
        if (Auth::guard('web')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            // Evitar redirigir a endpoints JSON como /wishlist/count
            $intended = session('url.intended');
            if ($intended && $this->isSafeRedirect($intended)) {
                return redirect()->intended(route('home'));
            }
            return redirect()->route('home');
        }

        // Intentar login como empresa
        if (Auth::guard('empresa')->attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            $intended = session('url.intended');
            if ($intended && $this->isSafeRedirect($intended)) {
                return redirect()->intended(route('empresa.dashboard'));
            }
            return redirect()->route('empresa.dashboard');
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

    /**
     * Determina si la URL intended es segura para redirigir (no endpoints JSON/API).
     */
    private function isSafeRedirect(string $url): bool
    {
        $path = parse_url($url, PHP_URL_PATH) ?? '';
        // Endpoints que NO deben ser destino después de login
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
