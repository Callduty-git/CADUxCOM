<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Subcategoria;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::all();
        return view('productos.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $subcategorias = Subcategoria::all();
        $empresas = Empresa::all();
        return view('productos.create', compact('subcategorias', 'empresas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'Fecha_Caducidad' => 'nullable|date',
            'Cantidad' => 'required|integer|min:0',
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Descripcion' => 'nullable|string',
            'Precio' => 'required|numeric|min:0',
            'Tipo' => 'required|string|max:50',
            'Codigo' => 'required|string|max:255|unique:productos,Codigo',
            'Id_Empresa' => 'required|integer|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|integer|exists:subcategorias,Id_Subcategoria',
        ]);

        // Guardar la foto si se envió
        if ($request->hasFile('Foto')) {
            $path = $request->file('Foto')->store('productos', 'public');
            $validatedData['Foto'] = $path;
        }

        Producto::create($validatedData);

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
        $subcategorias = Subcategoria::all();
        $empresas = Empresa::all();
        return view('productos.edit', compact('producto', 'subcategorias', 'empresas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validatedData = $request->validate([
            'Nombre' => 'required|string|max:255',
            'Marca' => 'required|string|max:255',
            'Fecha_Caducidad' => 'nullable|date',
            'Cantidad' => 'required|integer|min:0',
            'Foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'Descripcion' => 'nullable|string',
            'Precio' => 'required|numeric|min:0',
            'Tipo' => 'required|string|max:50',
            'Codigo' => 'required|string|max:255|unique:productos,Codigo,' . $producto->Id_Producto . ',Id_Producto',
            'Id_Empresa' => 'required|integer|exists:empresas,Id_Empresa',
            'Id_Subcategoria' => 'required|integer|exists:subcategorias,Id_Subcategoria',
        ]);

        // Reemplazar la foto si se envió una nueva
        if ($request->hasFile('Foto')) {
            // Borrar la foto anterior si existe
            if ($producto->Foto && Storage::disk('public')->exists($producto->Foto)) {
                Storage::disk('public')->delete($producto->Foto);
            }

            // Guardar la nueva
            $path = $request->file('Foto')->store('productos', 'public');
            $validatedData['Foto'] = $path;
        }

        $producto->update($validatedData);

        return redirect()->route('productos.index')->with('success', 'Producto actualizado exitosamente!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        // Eliminar la foto del storage si existe
        if ($producto->Foto && Storage::disk('public')->exists($producto->Foto)) {
            Storage::disk('public')->delete($producto->Foto);
        }

        $producto->delete();

        return redirect()->route('productos.index')->with('success', 'Producto eliminado exitosamente!');
    }
}