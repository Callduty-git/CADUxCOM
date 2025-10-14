<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Listado de productos para administradores con búsqueda y paginación.
     */
    public function index(Request $request)
    {
        $q = trim((string)$request->input('q'));

        $productos = Producto::with(['empresa', 'subcategoria.categoria'])
            ->when($q, function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('Nombre', 'like', "%{$q}%")
                        ->orWhere('Marca', 'like', "%{$q}%")
                        ->orWhereHas('empresa', function ($e) use ($q) {
                            $e->where('Nombre', 'like', "%{$q}%");
                        });
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.products.index', compact('productos'));
    }
}