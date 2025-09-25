<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\Empresa;
use App\Models\LogEmpresa; // Ya importado, ¡genial!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    public function publicIndex(Request $request)
    {
        $query = $request->input('query');
        $categoria = $request->input('categoria');
        $subcategoria = $request->input('subcategoria');

        $productos = Producto::with(['empresa', 'subcategoria.categoria'])
            ->when($query, function ($q) use ($query) {
                $q->where('Nombre', 'like', "%{$query}%")
                  ->orWhere('Marca', 'like', "%{$query}%");
            })
            ->when($categoria, function ($q) use ($categoria) {
                $q->whereHas('subcategoria', function ($subQuery) use ($categoria) {
                    $subQuery->where('Id_Categoria', $categoria);
                });
            })
            ->when($subcategoria, function ($q) use ($subcategoria) {
                $q->where('Id_Subcategoria', $subcategoria);
            })
            ->where('Cantidad', '>', 0) // Solo productos disponibles
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $categorias = \App\Models\Categoria::with('subcategorias')->get();
        $subcategorias = \App\Models\Subcategoria::all();

        return view('productos.public-index', compact('productos', 'categorias', 'subcategorias'));
    }

    public function index(Request $request)
    {
        $query = $request->input('query');
        $categoria = $request->input('categoria');
        $subcategoria = $request->input('subcategoria');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $precioMin = $request->input('precio_min');
        $precioMax = $request->input('precio_max');
        $disponibilidad = $request->input('disponibilidad');

        $productos = Producto::with(['subcategoria.categoria'])
            ->when($query, function ($q) use ($query) {
                $q->where('Nombre', 'like', "%{$query}%")
                  ->orWhere('Marca', 'like', "%{$query}%");
            })
            ->when($request->has('categoria'), function ($q) use ($request) {
                $categorias = $request->input('categoria', []);
                if (!empty($categorias)) {
                    $q->whereHas('subcategoria.categoria', function ($subQuery) use ($categorias) {
                        $subQuery->whereIn('Id_Categoria', $categorias);
                    });
                }
            })
            ->when($request->has('subcategoria'), function ($q) use ($request) {
                $subcategorias = $request->input('subcategoria', []);
                if (!empty($subcategorias)) {
                    $q->whereIn('Id_Subcategoria', $subcategorias);
                }
            })
            ->when($fechaDesde, function ($q) use ($fechaDesde) {
                $q->where('Fecha_Caducidad', '>=', $fechaDesde);
            })
            ->when($fechaHasta, function ($q) use ($fechaHasta) {
                $q->where('Fecha_Caducidad', '<=', $fechaHasta);
            })
            ->when($precioMin, function ($q) use ($precioMin) {
                $q->where('Precio', '>=', $precioMin);
            })
            ->when($precioMax, function ($q) use ($precioMax) {
                $q->where('Precio', '<=', $precioMax);
            })
            ->when($disponibilidad, function ($q) use ($disponibilidad) {
                switch ($disponibilidad) {
                    case 'disponible':
                        $q->where('Fecha_Caducidad', '>', now()->addDays(7));
                        break;
                    case 'por_vencer':
                        $q->where('Fecha_Caducidad', '<=', now()->addDays(7))
                          ->where('Fecha_Caducidad', '>', now());
                        break;
                    case 'agotado':
                        $q->where('Fecha_Caducidad', '<=', now());
                        break;
                }
            })
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['productos' => $productos]);
        }

        // Obtener categorías y subcategorías para el filtro
        $categorias = \App\Models\Categoria::all();
        $subcategorias = \App\Models\Subcategoria::with('categoria')->get();

        return view('productos.index', compact('productos', 'categorias', 'subcategorias'));
    }
    

    public function create()
    {
        $subcategorias = Subcategoria::all();

        if (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            return view('productos.create', [
                'subcategorias' => $subcategorias,
                'empresas' => collect([$empresa]) // solo su empresa
            ]);
        }

        // Para usuarios normales
        $empresas = Empresa::all();
        return view('productos.create', compact('empresas', 'subcategorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'PrecioOriginal' => 'required|numeric|min:0',
            'Precio' => 'required|numeric|min:0',
            'Fecha_Caducidad' => 'nullable|date',
            'Id_Empresa' => 'required|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|exists:subcategorias,Id_Subcategoria',
            'Foto' => 'nullable|image|max:2048',
        ], [
            'Nombre.required' => 'El nombre es obligatorio.',
            'Marca.required' => 'La marca es obligatoria.',
            'PrecioOriginal.required' => 'El precio original es obligatorio.',
            'Precio.required' => 'El precio es obligatorio.',
            'PrecioOriginal.min' => 'El precio original no puede ser negativo.',
            'Precio.min' => 'El precio no puede ser negativo.',
            'Id_Empresa.required' => 'La empresa es obligatoria.',
            'Id_Empresa.exists' => 'La empresa seleccionada no existe.',
            'Id_Subcategoria.required' => 'La subcategoría es obligatoria.',
            'Id_Subcategoria.exists' => 'La subcategoría seleccionada no existe.',
            'Foto.image' => 'El archivo debe ser una imagen.',
            'Foto.max' => 'La imagen no debe superar los 2MB.'
        ]);

        // Validación extra: el precio no puede ser mayor al precio original
        if ($request->Precio > $request->PrecioOriginal) {
            return back()->withInput()->with('error', 'El precio de oferta no puede ser mayor al precio original.');
        }

        $producto = new Producto($request->except('Foto'));

        if ($request->hasFile('Foto')) {
            $producto->Foto = $request->file('Foto')->store('productos', 'public');
        }

        $producto->save();

        // Registrar la acción de "agregar producto" en el log
        LogEmpresa::create([
            'empresa_id' => $producto->Id_Empresa,
            'accion' => 'Se agregó un producto',
            'descripcion' => $producto->Nombre,
            'created_at' => now(), // Asegúrate de registrar la hora exacta
        ]);

        // Lógica para limitar a 100 registros para la empresa actual
        $maxLogs = 100; // Define el límite de logs
        $logsCount = LogEmpresa::where('empresa_id', $producto->Id_Empresa)->count(); // Contar logs para esta empresa

        if ($logsCount > $maxLogs) {
            // Obtener los logs más antiguos para esta empresa y eliminarlos hasta que queden 100
            $oldestLogs = LogEmpresa::where('empresa_id', $producto->Id_Empresa)
                                    ->orderBy('created_at', 'asc')
                                    ->take($logsCount - $maxLogs)
                                    ->get();

            foreach ($oldestLogs as $oldLog) {
                $oldLog->delete();
            }
        }

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto creado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    public function show($id, Request $request)
    {
        $producto = Producto::findOrFail($id);
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($producto);
        }
        return view('productos.show', compact('producto'));
    }

    public function userShow($id)
    {
        $producto = Producto::with(['empresa', 'subcategoria.categoria'])->findOrFail($id);
        return view('productos.user-detail', compact('producto'));
    }

    public function edit($id)
    {
        $producto = Producto::findOrFail($id);
        $subcategorias = Subcategoria::all();

        if (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            return view('productos.edit', [
                'producto' => $producto,
                'subcategorias' => $subcategorias,
                'empresas' => collect([$empresa])
            ]);
        }

        $empresas = Empresa::all();
        return view('productos.edit', compact('producto', 'empresas', 'subcategorias'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'PrecioOriginal' => 'required|numeric|min:0',
            'Precio' => 'required|numeric|min:0',
            'Fecha_Caducidad' => 'nullable|date',
            'Id_Empresa' => 'required|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|exists:subcategorias,Id_Subcategoria',
            'Foto' => 'nullable|image|max:2048',
        ], [
            'Nombre.required' => 'El nombre es obligatorio.',
            'Marca.required' => 'La marca es obligatoria.',
            'PrecioOriginal.required' => 'El precio original es obligatorio.',
            'Precio.required' => 'El precio es obligatorio.',
            'PrecioOriginal.min' => 'El precio original no puede ser negativo.',
            'Precio.min' => 'El precio no puede ser negativo.',
            'Id_Empresa.required' => 'La empresa es obligatoria.',
            'Id_Empresa.exists' => 'La empresa seleccionada no existe.',
            'Id_Subcategoria.required' => 'La subcategoría es obligatoria.',
            'Id_Subcategoria.exists' => 'La subcategoría seleccionada no existe.',
            'Foto.image' => 'El archivo debe ser una imagen.',
            'Foto.max' => 'La imagen no debe superar los 2MB.'
        ]);

        if ($request->Precio > $request->PrecioOriginal) {
            return back()->withInput()->with('error', 'El precio de oferta no puede ser mayor al precio original.');
        }

        $producto = Producto::findOrFail($id);
        $producto->fill($request->except('Foto'));

        if ($request->hasFile('Foto')) {
            if ($producto->Foto) {
                Storage::disk('public')->delete($producto->Foto);
            }
            $producto->Foto = $request->file('Foto')->store('productos', 'public');
        }

        $producto->save();

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto actualizado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    public function destroy($id)
    {
        $producto = Producto::findOrFail($id);

        // Guarda el nombre del producto antes de eliminarlo para el log
        $nombreProducto = $producto->Nombre;
        $empresaId = $producto->Id_Empresa; // Obtener el ID de la empresa del producto

        if ($producto->Foto) {
            Storage::disk('public')->delete($producto->Foto);
        }

        $producto->delete();

        // Registra la acción de "eliminar producto" en el log
        LogEmpresa::create([
            'empresa_id' => $empresaId, // Asocia el log a la empresa correcta
            'accion' => 'Se eliminó un producto',
            'descripcion' => $nombreProducto,
            'created_at' => now(), // Asegúrate de registrar la hora exacta de la eliminación
        ]);

        // Lógica para limitar a 100 registros después de la eliminación para la empresa actual
        $maxLogs = 100; // Define el límite de logs
        $logsCount = LogEmpresa::where('empresa_id', $empresaId)->count(); // Contar logs para esta empresa

        if ($logsCount > $maxLogs) {
            // Obtener los logs más antiguos para esta empresa y eliminarlos hasta que queden 100
            $oldestLogs = LogEmpresa::where('empresa_id', $empresaId)
                                    ->orderBy('created_at', 'asc')
                                    ->take($logsCount - $maxLogs)
                                    ->get();

            foreach ($oldestLogs as $oldLog) {
                $oldLog->delete();
            }
        }

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto eliminado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente.');
    }
}
