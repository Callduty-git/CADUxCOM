<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\Empresa;
use App\Models\LogEmpresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ProductoController extends Controller
{
    /**
     * Mostrar productos públicos
     */
    public function publicIndex(Request $request)
    {
        $query = $request->input('query');
        $categoria = $request->input('categoria');
        $subcategoria = $request->input('subcategoria');

        $productos = Producto::with(['empresa', 'subcategoria.categoria'])
            ->when($query, fn($q) => $q->where('Nombre', 'like', "%{$query}%")
                ->orWhere('Marca', 'like', "%{$query}%"))
            ->when($categoria, fn($q) => $q->whereHas('subcategoria', fn($sq) => $sq->where('Id_Categoria', $categoria)))
            ->when($subcategoria, fn($q) => $q->where('Id_Subcategoria', $subcategoria))
            ->where('Cantidad', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $categorias = \App\Models\Categoria::with('subcategorias')->get();
        $subcategorias = \App\Models\Subcategoria::all();

        return view('productos.public-index', compact('productos', 'categorias', 'subcategorias'));
    }

    /**
     * Listar productos (dashboard)
     */
    public function index(Request $request)
    {
        // Obtener la empresa autenticada
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) {
            abort(403, 'Acceso no autorizado.');
        }

        $query = $request->input('query');
        $categoria = $request->input('categoria');
        $subcategoria = $request->input('subcategoria');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $precioMin = $request->input('precio_min');
        $precioMax = $request->input('precio_max');
        $disponibilidad = $request->input('disponibilidad');

        $productos = Producto::with(['subcategoria.categoria'])
            ->where('Id_Empresa', $empresa->Id_Empresa) // FILTRAR POR EMPRESA
            ->when($query, fn($q) => $q->where('Nombre', 'like', "%{$query}%")
                ->orWhere('Marca', 'like', "%{$query}%"))
            ->when($categoria, fn($q) => $q->whereHas('subcategoria.categoria', fn($sq) => $sq->whereIn('Id_Categoria', (array) $categoria)))
            ->when($subcategoria, fn($q) => $q->whereIn('Id_Subcategoria', (array) $subcategoria))
            ->when($fechaDesde, fn($q) => $q->where('Fecha_Caducidad', '>=', $fechaDesde))
            ->when($fechaHasta, fn($q) => $q->where('Fecha_Caducidad', '<=', $fechaHasta))
            ->when($precioMin, fn($q) => $q->where('Precio', '>=', $precioMin))
            ->when($precioMax, fn($q) => $q->where('Precio', '<=', $precioMax))
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

        $categorias = \App\Models\Categoria::all();
        $subcategorias = \App\Models\Subcategoria::with('categoria')->get();

        return view('productos.index', compact('productos', 'categorias', 'subcategorias'));
    }

    /**
     * Crear producto
     */
    public function create()
    {
        $subcategorias = Subcategoria::all();

        if (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            return view('productos.create', [
                'subcategorias' => $subcategorias,
                'empresas' => collect([$empresa])
            ]);
        }

        $empresas = Empresa::all();
        return view('productos.create', compact('empresas', 'subcategorias'));
    }

    /**
     * Guardar producto
     */
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
        ]);

        // Extra de la primera: precio oferta no mayor al original
        if ($request->Precio > $request->PrecioOriginal) {
            return back()->withInput()->with('error', 'El precio de oferta no puede ser mayor al precio original.');
        }

        $producto = new Producto($request->except('Foto'));
        if ($request->hasFile('Foto')) {
            $producto->Foto = $request->file('Foto')->store('productos', 'public');
        }
        $producto->save();

        $this->manageLogLimit($producto->Id_Empresa);

        LogEmpresa::create([
            'empresa_id' => $producto->Id_Empresa,
            'accion' => 'Se agregó un producto',
            'descripcion' => $producto->Nombre,
            'created_at' => now(),
        ]);

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto creado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Mostrar producto (vista para usuario)
     */
    public function userShow($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.user-detail', compact('producto'));
    }

    /**
     * Mostrar producto (vista general)
     */
    public function show($id)
    {
        $producto = Producto::findOrFail($id);
        return view('productos.user-detail', compact('producto'));
    }

    /**
     * Mostrar producto (vista para empresa)
     */
    public function showEmpresa($id)
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) {
            abort(403, 'Acceso no autorizado.');
        }

        $producto = Producto::where('Id_Producto', $id)
            ->where('Id_Empresa', $empresa->Id_Empresa)
            ->firstOrFail();
            
        return view('productos.show-empresa', compact('producto'));
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        // Si es una empresa, verificar que el producto le pertenezca
        if (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            $producto = Producto::where('Id_Producto', $id)
                ->where('Id_Empresa', $empresa->Id_Empresa)
                ->firstOrFail();
        } else {
            $producto = Producto::findOrFail($id);
        }

        if ($producto->Foto) {
            Storage::disk('public')->delete($producto->Foto);
        }

        $producto->delete();

        $this->manageLogLimit($producto->Id_Empresa);

        LogEmpresa::create([
            'empresa_id' => $producto->Id_Empresa,
            'accion' => 'Se eliminó un producto',
            'descripcion' => $producto->Nombre,
            'created_at' => now(),
        ]);

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    /**
 * Editar producto
 */
public function edit($id)
{
    $subcategorias = Subcategoria::all();

    // Si quien edita es una empresa, usa la vista exclusiva de empresa
    if (Auth::guard('empresa')->check()) {
        $empresa = Auth::guard('empresa')->user();
        
        // Verificar que el producto pertenezca a la empresa autenticada
        $producto = Producto::where('Id_Producto', $id)
            ->where('Id_Empresa', $empresa->Id_Empresa)
            ->firstOrFail();
            
        return view('productos.edit-empresa', [
            'producto' => $producto,
            'subcategorias' => $subcategorias,
            'empresas' => collect([$empresa]) // Solo la empresa autenticada
        ]);
    }

    // Si es un administrador u otro rol, muestra la vista general
    $producto = Producto::findOrFail($id);
    $empresas = Empresa::all();
    return view('productos.edit', compact('producto', 'empresas', 'subcategorias'));
}


    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        // Si es una empresa, verificar que el producto le pertenezca
        if (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            $producto = Producto::where('Id_Producto', $id)
                ->where('Id_Empresa', $empresa->Id_Empresa)
                ->firstOrFail();
        } else {
            $producto = Producto::findOrFail($id);
        }

        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'PrecioOriginal' => 'required|numeric|min:0',
            'Precio' => 'required|numeric|min:0',
            'Fecha_Caducidad' => 'nullable|date',
            'Id_Empresa' => 'required|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|exists:subcategorias,Id_Subcategoria',
            'Foto' => 'nullable|image|max:2048',
        ]);

        // Validar precio de oferta no mayor al original
        if ($request->Precio > $request->PrecioOriginal) {
            return back()->withInput()->with('error', 'El precio de oferta no puede ser mayor al precio original.');
        }

        // Actualizar datos del producto
        $producto->fill($request->except('Foto'));

        // Manejar nueva foto si se subió
        if ($request->hasFile('Foto')) {
            // Eliminar foto anterior si existe
            if ($producto->Foto) {
                Storage::disk('public')->delete($producto->Foto);
            }
            $producto->Foto = $request->file('Foto')->store('productos', 'public');
        }

        $producto->save();

        $this->manageLogLimit($producto->Id_Empresa);

        LogEmpresa::create([
            'empresa_id' => $producto->Id_Empresa,
            'accion' => 'Se editó un producto',
            'descripcion' => $producto->Nombre,
            'created_at' => now(),
        ]);

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto actualizado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Manejar límite de logs (máximo 50)
     */
    private function manageLogLimit($empresaId)
    {
        $logs = LogEmpresa::where('empresa_id', $empresaId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($logs->count() >= 50) {
            $logs->slice(50)->each->delete();
        }
    }
}
