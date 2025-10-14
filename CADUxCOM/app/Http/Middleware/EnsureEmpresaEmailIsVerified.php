<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmpresaEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $empresa = Auth::guard('empresa')->user();
        \Log::info('🔍 Middleware verificando email', [
            'empresa_id' => $empresa?->id,
            'email_verified' => $empresa?->email_verified_at ? 'Sí' : 'No',
            'url' => $request->url()
        ]);
        
        if ($empresa && is_null($empresa->email_verified_at)) {
            \Log::info('📧 Redirigiendo a verificación de email');
            return redirect()->route('verification.notice')
                ->with('error', 'Debes verificar tu email antes de acceder. Revisa tu bandeja de entrada.');
        }

        \Log::info('✅ Email verificado, continuando');
        return $next($request);
    }
}
