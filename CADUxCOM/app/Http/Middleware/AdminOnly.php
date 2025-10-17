<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminOnly
{
    /**
     * Maneja una petición entrante.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // Verificar si el usuario está autenticado
        if (!$user) {
            Log::warning('Intento de acceso no autenticado al panel de administrador', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);
            
            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Debes iniciar sesión para acceder al panel de administrador.']);
        }

        // Verificar si el usuario tiene rol de administrador
        if (($user->role ?? null) !== 'admin') {
            Log::warning('Intento de acceso no autorizado al panel de administrador', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role ?? 'sin_rol',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl()
            ]);

            // Cerrar sesión del usuario no autorizado
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->withErrors(['email' => 'Acceso restringido: solo administradores pueden acceder a este panel.']);
        }

        // Log de acceso exitoso para auditoría
        Log::info('Acceso autorizado al panel de administrador', [
            'user_id' => $user->id,
            'user_email' => $user->email,
            'ip' => $request->ip(),
            'url' => $request->fullUrl()
        ]);

        return $next($request);
    }
}