<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class CentroAyudaController extends Controller
{
    /**
     * Muestra la vista principal del centro de ayuda
     */
    public function index()
    {
        return view('ayuda.index');
    }

    /**
     * Redirige a la vista de perfil según el tipo de usuario autenticado
     */
    public function miCuenta()
    {
        // Verificar primero si es un usuario normal (guard web)
        if (Auth::guard('web')->check()) {
            return redirect()->route('profile.edit');
        }

        // Verificar si es una empresa autenticada (guard empresa)
        if (Auth::guard('empresa')->check()) {
            return redirect()->route('empresa.dashboard');
        }

        // Si no hay ninguna sesión activa, redirigir al login
        return redirect()->route('login')->with('message', 'Debes iniciar sesión para acceder a tu cuenta.');
    }

    /**
     * Muestra los pedidos del usuario autenticado
     */
    public function pedidos()
    {
        // Verificar primero si es un usuario normal (guard web)
        if (Auth::guard('web')->check()) {
            $orders = Order::where('user_id', Auth::guard('web')->id())
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);
            
            return view('ayuda.pedidos', compact('orders'));
        }

        // Si es una empresa autenticada, redirigir al dashboard de empresa
        if (Auth::guard('empresa')->check()) {
            return redirect()->route('empresa.dashboard');
        }

        // Si no hay ninguna sesión activa, redirigir al login
        return redirect()->route('login')->with('message', 'Debes iniciar sesión para ver tus pedidos.');
    }

    /**
     * Muestra información sobre métodos de pago
     */
    public function pagos()
    {
        return view('ayuda.pagos');
    }

    /**
     * Muestra información sobre entrega y envíos
     */
    public function entrega()
    {
        return view('ayuda.entrega');
    }
}