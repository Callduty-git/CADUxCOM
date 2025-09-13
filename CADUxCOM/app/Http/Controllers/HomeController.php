<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function index()
    {
        $productos = \App\Models\Producto::with(['empresa', 'subcategoria', 'subcategoria.categoria'])
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $categorias = \App\Models\Categoria::with('subcategorias')->get();
        $subcategorias = \App\Models\Subcategoria::all();

        return view('home', compact('productos', 'categorias', 'subcategorias'));
    }
}
