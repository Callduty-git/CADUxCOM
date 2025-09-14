<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LogEmpresa;

class EmpresaDashboardController extends Controller
{
    /**
     * Muestra el dashboard de la empresa autenticada.
     */
    public function index()
    {
        // Obtiene la empresa autenticada
        $empresa = Auth::guard('empresa')->user();

        // Retorna la vista con la variable $empresa
        return view('empresa.dashboard', compact('empresa'));
    }

    /**
     * Muestra la vista de Facturas (consola de actividades).
     */
    public function facturas()
    {
        // Trae los logs con relación a la empresa
        $logs = LogEmpresa::with('empresa')->get();

        return view('facturas.index', compact('logs'));
    }
}
