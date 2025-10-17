<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Empresa;
use App\Models\User;
use App\Models\Comentario;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Obtener estadísticas para el dashboard
        $totalEmpresas = Empresa::count();
        $totalUsuarios = User::where('role', '!=', 'admin')->count();
        $totalResenas = Comentario::count();
        $empresasPendientes = Empresa::where('status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalEmpresas',
            'totalUsuarios', 
            'totalResenas',
            'empresasPendientes'
        ));
    }
}