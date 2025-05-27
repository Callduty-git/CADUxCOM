<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Producto</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        div { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: calc(100% - 22px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box; /* Para que el padding no aumente el ancho total */
        }
        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #45a049;
        }
        .error { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <h1>Crear Nuevo Producto</h1>

    <form action="{{ route('productos.store') }}" method="POST">
        @csrf {{-- ¡Esto es CRUCIAL para la seguridad en Laravel! --}}

        {{-- Campo Nombre --}}
        <div>
            <label for="Nombre">Nombre:</label>
            <input type="text" id="Nombre" name="Nombre" value="{{ old('Nombre') }}" required>
            @error('Nombre')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Marca --}}
        <div>
            <label for="Marca">Marca:</label>
            <input type="text" id="Marca" name="Marca" value="{{ old('Marca') }}" required>
            @error('Marca')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Fecha de Caducidad --}}
        <div>
            <label for="Fecha_Caducidad">Fecha de Caducidad:</label>
            <input type="date" id="Fecha_Caducidad" name="Fecha_Caducidad" value="{{ old('Fecha_Caducidad') }}">
            @error('Fecha_Caducidad')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Cantidad --}}
        <div>
            <label for="Cantidad">Cantidad:</label>
            <input type="number" id="Cantidad" name="Cantidad" value="{{ old('Cantidad') }}" required min="0">
            @error('Cantidad')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Descripción --}}
        <div>
            <label for="Descripcion">Descripción:</label>
            <textarea id="Descripcion" name="Descripcion" rows="4">{{ old('Descripcion') }}</textarea>
            @error('Descripcion')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Precio --}}
        <div>
            <label for="Precio">Precio:</label>
            <input type="number" id="Precio" name="Precio" value="{{ old('Precio') }}" step="0.01" required min="0">
            @error('Precio')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Tipo (por ejemplo, Unidad, Kilo, Litro) --}}
        <div>
            <label for="Tipo">Tipo:</label>
            <input type="text" id="Tipo" name="Tipo" value="{{ old('Tipo') }}" required>
            @error('Tipo')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Código --}}
        <div>
            <label for="Codigo">Código:</label>
            <input type="text" id="Codigo" name="Codigo" value="{{ old('Codigo') }}" required>
            @error('Codigo')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Id_Empresa (por ahora, fijo en 1. Luego se puede hacer dinámico) --}}
        {{-- Puedes hacerlo oculto si no quieres que el usuario lo vea/edite --}}
        <div>
            <label for="Id_Empresa">ID Empresa:</label>
            <input type="number" id="Id_Empresa" name="Id_Empresa" value="1" required readonly>
            @error('Id_Empresa')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Id_Subcategoria (Necesitarás llenar esto dinámicamente) --}}
        {{-- Por ahora, pondremos un campo de texto simple. Luego lo haremos un select. --}}
        <div>
            <label for="Id_Subcategoria">ID Subcategoría:</label>
            <input type="number" id="Id_Subcategoria" name="Id_Subcategoria" value="{{ old('Id_Subcategoria') }}" required>
            @error('Id_Subcategoria')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Puedes agregar un campo para la Foto, pero necesitarás manejar la carga de archivos --}}
        {{-- <div>
            <label for="Foto">Foto (URL o Carga):</label>
            <input type="text" id="Foto" name="Foto" value="{{ old('Foto') }}">
            @error('Foto')
                <div class="error">{{ $message }}</div>
            @enderror
        </div> --}}


        <button type="submit">Guardar Producto</button>
    </form>

    <br>
    <a href="{{ route('productos.index') }}">Volver a la lista de productos</a>
</body>
</html>