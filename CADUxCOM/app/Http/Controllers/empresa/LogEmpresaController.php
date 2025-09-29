<?php

namespace App\Http\Controllers\empresa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogEmpresa;

class LogEmpresaController extends Controller
{
    /**
     * Mostrar listado de logs de la empresa autenticada.
     */
    public function index()
    {
        // Verificamos que el usuario autenticado sea una empresa
        $empresa = Auth::guard('empresa')->user();

        if (!$empresa) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión como empresa.');
        }

        // Obtener logs de esta empresa
        $logs = LogEmpresa::where('empresa_id', $empresa->Id_Empresa)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('facturas.index', compact('logs'));
    }
}
