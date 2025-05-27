<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Subcategoria; // Importar el modelo Subcategoria para la validación y el select
use App\Models\Empresa;      // Importar el modelo Empresa para la validación y el select
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all(); // Obtener todos los productos
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Obtener todas las subcategorías y empresas para los dropdowns del formulario
        $subcategorias = Subcategoria::all();
        $empresas = Empresa::all();

        return view('productos.create', compact('subcategorias', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validación de los datos del formulario
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'Fecha_Caducidad' => 'nullable|date',
            'Cantidad' => 'required|integer|min:0',
            'Foto' => 'nullable|string|max:255', // Si la foto se sube como archivo, esto cambiará
            'Descripcion' => 'nullable|string',
            'Precio' => 'required|numeric|min:0',
            'Tipo' => 'required|string|max:50',
            'Codigo' => 'required|string|max:255|unique:productos,Codigo', // Código debe ser único
            'Id_Empresa' => 'required|integer|exists:empresas,Id_Empresa', // Debe existir en la tabla empresas
            'Id_Subcategoria' => 'required|integer|exists:subcategorias,Id_Subcategoria', // Debe existir en la tabla subcategorias
        ]);

        // 2. Crear el nuevo producto en la base de datos
        Producto::create($validatedData);

        // 3. Redirigir al usuario de vuelta a la lista de productos con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto creado exitosamente!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return view('productos.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        // Obtener todas las subcategorías y empresas para los dropdowns del formulario
        $subcategorias = Subcategoria::all();
        $empresas = Empresa::all();

        return view('productos.edit', compact('producto', 'subcategorias', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        // 1. Validación de los datos actualizados
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'Fecha_Caducidad' => 'nullable|date',
            'Cantidad' => 'required|integer|min:0',
            'Foto' => 'nullable|string|max:255',
            'Descripcion' => 'nullable|string',
            'Precio' => 'required|numeric|min:0',
            'Tipo' => 'required|string|max:50',
            // El código debe ser único, pero ignorando el propio código del producto que se está editando
            'Codigo' => 'required|string|max:255|unique:productos,Codigo,' . $producto->Id_Producto . ',Id_Producto',
            'Id_Empresa' => 'required|integer|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|integer|exists:subcategorias,Id_Subcategoria',
        ]);

        // 2. Actualizar el producto en la base de datos
        $producto->update($validatedData);

        // 3. Redirigir al usuario con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        // Eliminar el producto
        $producto->delete();

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente!');
    }
}