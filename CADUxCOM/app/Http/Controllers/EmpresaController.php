<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Asegúrate de que Auth esté importado

class EmpresaController extends Controller
{
    /**
     * Muestra el dashboard de la empresa con sus datos.
     *
     * @return \Illuminate\View\View
     */
    public function dashboard()
    {
        // Obtener el usuario autenticado (la empresa) usando el guard 'empresa'
        // Esto asignará la instancia del modelo Empresa a la variable $empresa
        $empresa = Auth::guard('empresa')->user();

        // Pasar la variable $empresa a la vista 'empresa.dashboard'
        // El método compact('empresa') es una forma abreviada de ['empresa' => $empresa]
        return view('empresa.dashboard', compact('empresa'));
    }
}
