<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="{{ asset('css/productos-edit.css') }}">
</head>
<body>
<div class="container">
    <h1>Editar Producto</h1>

    <form action="{{ route('productos.update', $producto->Id_Producto) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div>
            <label for="Nombre">Nombre:</label>
            <input type="text" name="Nombre" id="Nombre" value="{{ old('Nombre', $producto->Nombre) }}" required>
        </div>

        <div>
            <label for="Marca">Marca:</label>
            <input type="text" name="Marca" id="Marca" value="{{ old('Marca', $producto->Marca) }}" required>
        </div>

        <div>
            <label for="Codigo">Código:</label>
            <input type="text" name="Codigo" id="Codigo" value="{{ old('Codigo', $producto->Codigo) }}" required>
        </div>

        <div>
            <label for="Precio">Precio:</label>
            <input type="number" step="0.01" name="Precio" id="Precio" value="{{ old('Precio', $producto->Precio) }}" required>
        </div>

        <div>
            <label for="Cantidad">Cantidad:</label>
            <input type="number" name="Cantidad" id="Cantidad" value="{{ old('Cantidad', $producto->Cantidad) }}" required>
        </div>

        <div>
            <label for="Tipo">Tipo:</label>
            <input type="text" name="Tipo" id="Tipo" value="{{ old('Tipo', $producto->Tipo) }}" required>
        </div>

        <div>
            <label for="Fecha_Caducidad">Fecha de Caducidad:</label>
            <input type="date" name="Fecha_Caducidad" id="Fecha_Caducidad" value="{{ old('Fecha_Caducidad', $producto->Fecha_Caducidad) }}">
        </div>

        <div>
            <label for="Descripcion">Descripción:</label>
            <textarea name="Descripcion" id="Descripcion">{{ old('Descripcion', $producto->Descripcion) }}</textarea>
        </div>

        <div>
            <label for="Id_Empresa">Empresa:</label>
            <select name="Id_Empresa" id="Id_Empresa" required>
                <option value="">Seleccionar empresa</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->Id_Empresa }}" {{ $producto->Id_Empresa == $empresa->Id_Empresa ? 'selected' : '' }}>
                        {{ $empresa->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="Id_Subcategoria">Subcategoría:</label>
            <select name="Id_Subcategoria" id="Id_Subcategoria" required>
                <option value="">Seleccionar subcategoría</option>
                @foreach ($subcategorias as $sub)
                    <option value="{{ $sub->Id_Subcategoria }}" {{ $producto->Id_Subcategoria == $sub->Id_Subcategoria ? 'selected' : '' }}>
                        {{ $sub->Nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Foto actual:</label><br>
            @if ($producto->Foto)
                <img src="{{ asset('storage/' . $producto->Foto) }}" alt="Foto actual" style="max-width: 200px;"><br>
            @else
                No hay foto cargada.<br>
            @endif
        </div>

        <div>
            <label for="Foto">Nueva foto (opcional):</label>
            <input type="file" name="Foto" id="Foto">
        </div>

        <div class="button-group">
            <button type="submit">Actualizar</button>
            <a href="{{ route('productos.index') }}" class="button-link">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>