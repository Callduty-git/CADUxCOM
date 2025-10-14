<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    /**
     * Maneja una petición entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Permitir sólo usuarios autenticados con rol 'admin'
        if ($user && ($user->role ?? null) === 'admin') {
            return $next($request);
        }

        // Si no está autenticado o no es admin, cerrar sesión (si aplica) y redirigir al login admin
        if ($user) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route('admin.login')
            ->withErrors(['email' => 'Acceso restringido: sólo administradores.']);
    }
}