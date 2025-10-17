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
     * Mostrar productos por subcategoría
     */
    public function bySubcategory($subcategoriaId)
    {
        $subcategoria = Subcategoria::with('categoria')->findOrFail($subcategoriaId);
        
        $productos = Producto::with(['empresa', 'subcategoria.categoria'])
            ->where('Id_Subcategoria', $subcategoriaId)
            ->where('Cantidad', '>', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(24);

        $categorias = \App\Models\Categoria::with('subcategorias')->get();
        $subcategorias = \App\Models\Subcategoria::all();

        return view('productos.public-index', compact('productos', 'categorias', 'subcategorias', 'subcategoria'));
    }

    /**
     * Listar productos (dashboard)
     */
    public function index(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) abort(403, 'Acceso no autorizado.');

        $query = $request->input('query');
        $categoria = $request->input('categoria');
        $subcategoria = $request->input('subcategoria');
        $fechaDesde = $request->input('fecha_desde');
        $fechaHasta = $request->input('fecha_hasta');
        $precioMin = $request->input('precio_min');
        $precioMax = $request->input('precio_max');
        $disponibilidad = $request->input('disponibilidad');

        $productos = Producto::with(['subcategoria.categoria'])
            ->where('Id_Empresa', $empresa->Id_Empresa)
            ->when($query, fn($q) => $q->where('Nombre', 'like', "%{$query}%")
                ->orWhere('Marca', 'like', "%{$query}%"))
            ->when($categoria, fn($q) => $q->whereHas('subcategoria.categoria', fn($sq) => $sq->whereIn('Id_Categoria', (array)$categoria)))
            ->when($subcategoria, fn($q) => $q->whereIn('Id_Subcategoria', (array)$subcategoria))
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
        return view('productos.create', compact('subcategorias'));
    }

    /**
     * Guardar producto
     */
    public function store(Request $request)
    {
        if (Auth::guard('empresa')->check()) {
            $authEmpresa = Auth::guard('empresa')->user();
            $request->merge(['Id_Empresa' => $authEmpresa->Id_Empresa]);
        }

        $empresa = Empresa::find($request->input('Id_Empresa'));
        if ($empresa && $empresa->progressive_discount_enabled) {
            $precioOriginal = $request->input('PrecioOriginal');
            $request->merge(['Precio' => $precioOriginal]);
        }

        // Mensajes de error personalizados en español
        $messages = [
            'Nombre.required' => 'El nombre del producto es obligatorio.',
            'Marca.required' => 'La marca del producto es obligatoria.',
            'PrecioOriginal.required' => 'El precio original es obligatorio.',
            'PrecioOriginal.numeric' => 'El precio original debe ser un número.',
            'PrecioOriginal.min' => 'El precio original debe ser mayor o igual a 0.',
            'Precio.required' => 'El precio es obligatorio.',
            'Precio.numeric' => 'El precio debe ser un número.',
            'Precio.min' => 'El precio debe ser mayor o igual a 0.',
            'Fecha_Caducidad.date' => 'La fecha de caducidad debe ser una fecha válida.',
            'Fecha_Caducidad.after_or_equal' => 'La fecha de caducidad no puede ser una fecha pasada.',
            'Id_Empresa.required' => 'La empresa es obligatoria.',
            'Id_Empresa.exists' => 'La empresa seleccionada no existe.',
            'Id_Subcategoria.required' => 'La subcategoría es obligatoria.',
            'Id_Subcategoria.exists' => 'La subcategoría seleccionada no existe.',
            'Foto.image' => 'El archivo debe ser una imagen.',
            'Foto.max' => 'La imagen no debe superar los 2MB.',
        ];

        $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'PrecioOriginal' => 'required|numeric|min:0',
            'Precio' => 'required|numeric|min:0',
            'Fecha_Caducidad' => 'nullable|date|after_or_equal:today',
            'Id_Empresa' => 'required|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|exists:subcategorias,Id_Subcategoria',
            'Foto' => 'nullable|image|max:2048',
        ], $messages);

        if ($request->Precio > $request->PrecioOriginal) {
            return back()->withInput()->with('error', 'El precio de oferta no puede ser mayor al precio original.');
        }

        $producto = new Producto($request->except('Foto'));
        if ($request->hasFile('Foto')) {
            $producto->Foto = $request->file('Foto')->store('productos', 'public');
        }
        $producto->save();

        $this->logAction($producto->Id_Empresa, 'Se agregó un producto', $producto->Nombre);

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto creado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto creado exitosamente.');
    }

    /**
     * Mostrar producto (vista para usuario)
     */
    public function userShow($id)
    {
        $producto = Producto::with(['empresa', 'subcategoria.categoria'])->findOrFail($id);
        $productosRelacionados = $this->getRelatedProducts($producto);
        return view('productos.user-detail', compact('producto', 'productosRelacionados'));
    }

    private function getRelatedProducts($producto)
    {
        $sameSubcategory = Producto::with(['empresa', 'subcategoria.categoria'])
            ->where('Id_Subcategoria', $producto->Id_Subcategoria)
            ->where('Id_Producto', '!=', $producto->Id_Producto)
            ->where('Cantidad', '>', 0)
            ->limit(4)
            ->get();

        if ($sameSubcategory->count() < 4) {
            $sameCategory = Producto::with(['empresa', 'subcategoria.categoria'])
                ->whereHas('subcategoria', fn($q) => $q->where('Id_Categoria', $producto->subcategoria->Id_Categoria))
                ->where('Id_Producto', '!=', $producto->Id_Producto)
                ->whereNotIn('Id_Producto', $sameSubcategory->pluck('Id_Producto'))
                ->limit(4 - $sameSubcategory->count())
                ->get();
            $sameSubcategory = $sameSubcategory->merge($sameCategory);
        }

        if ($sameSubcategory->count() < 4) {
            $sameCompany = Producto::with(['empresa', 'subcategoria.categoria'])
                ->where('Id_Empresa', $producto->Id_Empresa)
                ->where('Id_Producto', '!=', $producto->Id_Producto)
                ->whereNotIn('Id_Producto', $sameSubcategory->pluck('Id_Producto'))
                ->limit(4 - $sameSubcategory->count())
                ->get();
            $sameSubcategory = $sameSubcategory->merge($sameCompany);
        }

        return $sameSubcategory->take(4);
    }

    /**
     * Mostrar producto (vista para empresa)
     */
    public function showEmpresa($id)
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) abort(403, 'Acceso no autorizado.');

        $producto = Producto::where('Id_Producto', $id)
            ->where('Id_Empresa', $empresa->Id_Empresa)
            ->firstOrFail();

        return view('productos.show-empresa', compact('producto'));
    }

    /**
     * Editar producto
     */
    public function edit($id)
    {
        $subcategorias = Subcategoria::all();

        if (Auth::guard('empresa')->check()) {
            $empresa = Auth::guard('empresa')->user();
            $producto = Producto::where('Id_Producto', $id)
                ->where('Id_Empresa', $empresa->Id_Empresa)
                ->firstOrFail();

            return view('productos.edit-empresa', [
                'producto' => $producto,
                'subcategorias' => $subcategorias,
                'empresas' => collect([$empresa])
            ]);
        }

        $producto = Producto::findOrFail($id);
        $empresas = Empresa::all();
        return view('productos.edit', compact('producto', 'empresas', 'subcategorias'));
    }

    /**
     * Actualizar producto
     */
    public function update(Request $request, $id)
    {
        $producto = Auth::guard('empresa')->check()
            ? Producto::where('Id_Producto', $id)
                ->where('Id_Empresa', Auth::guard('empresa')->user()->Id_Empresa)
                ->firstOrFail()
            : Producto::findOrFail($id);

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

        if ($request->Precio > $request->PrecioOriginal) {
            return back()->withInput()->with('error', 'El precio de oferta no puede ser mayor al precio original.');
        }

        $producto->fill($request->except('Foto'));

        if ($request->hasFile('Foto')) {
            if ($producto->Foto) {
                Storage::disk('public')->delete($producto->Foto);
            }
            $producto->Foto = $request->file('Foto')->store('productos', 'public');
        }

        $producto->save();

        $this->logAction($producto->Id_Empresa, 'Se editó un producto', $producto->Nombre);

        return Auth::guard('empresa')->check()
            ? redirect()->route('empresa.productos.index')->with('success', 'Producto actualizado exitosamente.')
            : redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente.');
    }

    /**
     * Eliminar producto
     */
    public function destroy($id)
    {
        $producto = Auth::guard('empresa')->check()
            ? Producto::where('Id_Producto', $id)
                ->where('Id_Empresa', Auth::guard('empresa')->user()->Id_Empresa)
                ->firstOrFail()
            : Producto::findOrFail($id);

        if ($producto->Foto) {
            Storage::disk('public')->delete($producto->Foto);
        }

        $producto->delete();

        $this->logAction($producto->Id_Empresa, 'Se eliminó un producto', $producto->Nombre);

        return back()->with('success', 'Producto eliminado correctamente.');
    }

    /**
     * Eliminar productos vencidos automáticamente
     */
    public function deleteExpired(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();
        if (!$empresa) abort(403, 'Acceso no autorizado.');

        $expiredProducts = Producto::where('Id_Empresa', $empresa->Id_Empresa)
            ->whereNotNull('Fecha_Caducidad')
            ->where('Fecha_Caducidad', '<=', now()->subDay())
            ->get();

        $deletedCount = 0;
        foreach ($expiredProducts as $producto) {
            if ($producto->Foto) Storage::disk('public')->delete($producto->Foto);
            $producto->delete();
            $deletedCount++;
        }

        $this->logAction($empresa->Id_Empresa, 'Se eliminaron productos vencidos', "Eliminados {$deletedCount} productos vencidos");

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'deleted_count' => $deletedCount,
                'message' => $deletedCount
                    ? "Se eliminaron {$deletedCount} productos vencidos."
                    : 'No hay productos vencidos para eliminar.'
            ]);
        }

        return back()->with('success', $deletedCount
            ? "Se eliminaron {$deletedCount} productos vencidos."
            : 'No hay productos vencidos para eliminar.');
    }

    /**
     * Registrar log y mantener máximo 50
     */
    private function logAction($empresaId, $accion, $descripcion)
    {
        LogEmpresa::create([
            'empresa_id' => $empresaId,
            'accion' => $accion,
            'descripcion' => $descripcion,
            'created_at' => now(),
        ]);

        $logs = LogEmpresa::where('empresa_id', $empresaId)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($logs->count() > 50) {
            $logs->slice(50)->each->delete();
        }
    }
}
