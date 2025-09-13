<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Buscar si el correo pertenece a una empresa
    $empresa = \App\Models\Empresa::where('email', $request->email)->first();

    if ($empresa) {
        // Intentar login como empresa
        if (Auth::guard('empresa')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ], $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/empresa/dashboard'); // Cambia si tienes otra ruta
        }
    }

    // Si no es empresa, intentar login como usuario normal
    if (Auth::guard('web')->attempt([
        'email' => $request->email,
        'password' => $request->password,
    ], $request->boolean('remember'))) {
        $request->session()->regenerate();
        return redirect()->intended('/dashboard'); // Usuario normal
    }

    return back()->withErrors([
        'email' => 'Las credenciales no coinciden con nuestros registros.',
    ])->onlyInput('email');
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
{
    if (Auth::guard('empresa')->check()) {
        Auth::guard('empresa')->logout();
    } elseif (Auth::guard('web')->check()) {
        Auth::guard('web')->logout();
    }

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
}

}
