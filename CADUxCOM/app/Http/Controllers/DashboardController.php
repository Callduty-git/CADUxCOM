<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;

class DashboardController extends Controller
{
    /**
     * Redirect users to home page instead of dashboard.
     * Dashboard is only for empresas.
     */
    public function index()
    {
        // Los usuarios normales deben ir al home, no al dashboard
        return redirect()->route('home');
    }
}