<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;
use App\Models\Subcategoria;

class CategoriaController extends Controller
{
    public function navbar()
    {
        $categorias = Categoria::all();
        $subcategorias = Subcategoria::all();

        // Agrupar las subcategorías por categoría
        $subcategoriasAgrupadas = [];

        foreach ($subcategorias as $sub) {
            $subcategoriasAgrupadas[$sub->Id_Categoria][] = $sub->Nombre;
        }

        return view('components.navbar', compact('categorias', 'subcategoriasAgrupadas'));
    }
}