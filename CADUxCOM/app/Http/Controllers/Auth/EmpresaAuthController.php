<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmpresaAuthController extends Controller
{
    public function __construct()
    {
        // Solo usuarios no autenticados en guard empresa pueden acceder a login
        $this->middleware('guest:empresa')->except('logout');
    }

    // Mostrar formulario de login para empresas
    public function showLoginForm()
    {
        return view('empresa.auth.login'); // crea esta vista
    }

    // Procesar login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('empresa')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('empresa.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con una empresa registrada.',
        ])->onlyInput('email');
    }

    // Logout empresa
    public function logout(Request $request)
    {
        Auth::guard('empresa')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect(route('empresa.login'));
    }
}
