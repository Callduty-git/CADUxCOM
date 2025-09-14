<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Empresa;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador SearchController - Sistema de búsqueda avanzada
 */
class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $categoria = $request->get('categoria');
        $subcategoria = $request->get('subcategoria');
        $empresa = $request->get('empresa');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $sortBy = $request->get('sort', 'relevance');

        // Log de la búsqueda
        if (Auth::check()) {
            ActivityLog::log(
                ActivityLog::ACTION_SEARCH_PERFORMED,
                "Búsqueda realizada: {$query}",
                ['query' => $query, 'filters' => $request->except(['q', 'page'])],
                Auth::id()
            );
        }

        // Construir consulta base
        $productosQuery = Producto::with(['empresa', 'subcategoria.categoria']);

        // Aplicar filtro de texto
        if (!empty($query)) {
            $productosQuery->where(function ($q) use ($query) {
                $q->where('Nombre', 'LIKE', "%{$query}%")
                  ->orWhere('Descripcion', 'LIKE', "%{$query}%")
                  ->orWhere('Marca', 'LIKE', "%{$query}%");
            });
        }

        // Aplicar filtros
        if ($categoria) {
            $productosQuery->whereHas('subcategoria', function ($q) use ($categoria) {
                $q->where('Id_Categoria', $categoria);
            });
        }

        if ($subcategoria) {
            $productosQuery->where('Id_Subcategoria', $subcategoria);
        }

        if ($empresa) {
            $productosQuery->where('Id_Empresa', $empresa);
        }

        if ($minPrice) {
            $productosQuery->where('Precio', '>=', $minPrice);
        }

        if ($maxPrice) {
            $productosQuery->where('Precio', '<=', $maxPrice);
        }

        // Aplicar ordenamiento
        switch ($sortBy) {
            case 'price_asc':
                $productosQuery->orderBy('Precio', 'asc');
                break;
            case 'price_desc':
                $productosQuery->orderBy('Precio', 'desc');
                break;
            case 'name_asc':
                $productosQuery->orderBy('Nombre', 'asc');
                break;
            case 'name_desc':
                $productosQuery->orderBy('Nombre', 'desc');
                break;
            default:
                $productosQuery->orderBy('created_at', 'desc');
                break;
        }

        $productos = $productosQuery->paginate(12)->withQueryString();
        $categorias = Categoria::with('subcategorias')->get();
        $subcategorias = Subcategoria::all();
        $empresas = Empresa::select('Id_Empresa', 'Nombre')->get();

        $searchStats = [
            'total_results' => $productos->total(),
            'query' => $query,
        ];

        return view('search.results', compact(
            'productos', 'categorias', 'subcategorias', 'empresas', 'searchStats'
        ));
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $productos = Producto::select('Id_Producto', 'Nombre', 'Marca', 'Precio')
            ->where(function ($q) use ($query) {
                $q->where('Nombre', 'LIKE', "%{$query}%")
                  ->orWhere('Marca', 'LIKE', "%{$query}%");
            })
            ->limit(5)
            ->get()
            ->map(function ($producto) {
                return [
                    'type' => 'product',
                    'id' => $producto->Id_Producto,
                    'title' => $producto->Nombre,
                    'subtitle' => $producto->Marca,
                    'price' => $producto->Precio,
                    'url' => route('productos.show', $producto->Id_Producto),
                ];
            });

        return response()->json($productos);
    }

    public function quickSearch(Request $request)
    {
        $query = $request->get('q', '');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $productos = Producto::with(['empresa'])
            ->where(function ($q) use ($query) {
                $q->where('Nombre', 'LIKE', "%{$query}%")
                  ->orWhere('Marca', 'LIKE', "%{$query}%");
            })
            ->limit(8)
            ->get()
            ->map(function ($producto) {
                return [
                    'id' => $producto->Id_Producto,
                    'name' => $producto->Nombre,
                    'brand' => $producto->Marca,
                    'price' => $producto->Precio,
                    'company' => $producto->empresa->Nombre,
                    'url' => route('productos.show', $producto->Id_Producto),
                ];
            });

        return response()->json($productos);
    }
}