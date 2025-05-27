<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto: {{ $producto->Nombre }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        form { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; background-color: #f9f9f9; }
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
            box-sizing: border-box;
        }
        button {
            background-color: #007bff; /* Azul para guardar cambios */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error { color: red; font-size: 0.9em; margin-top: 5px; }
        .back-link { display: block; text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <h1>Editar Producto: {{ $producto->Nombre }}</h1>

    <form action="{{ route('productos.update', $producto->Id_Producto) }}" method="POST">
        @csrf
        @method('PUT') {{-- ¡Esto es CRUCIAL para el método PUT en Laravel! --}}

        {{-- Campo Nombre --}}
        <div>
            <label for="Nombre">Nombre:</label>
            <input type="text" id="Nombre" name="Nombre" value="{{ old('Nombre', $producto->Nombre) }}" required>
            @error('Nombre')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Marca --}}
        <div>
            <label for="Marca">Marca:</label>
            <input type="text" id="Marca" name="Marca" value="{{ old('Marca', $producto->Marca) }}" required>
            @error('Marca')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Fecha de Caducidad --}}
        <div>
            <label for="Fecha_Caducidad">Fecha de Caducidad:</label>
            {{-- Formatear la fecha para que el input type="date" la reconozca correctamente --}}
            <input type="date" id="Fecha_Caducidad" name="Fecha_Caducidad" value="{{ old('Fecha_Caducidad', $producto->Fecha_Caducidad ? \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('Y-m-d') : '') }}">
            @error('Fecha_Caducidad')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Cantidad --}}
        <div>
            <label for="Cantidad">Cantidad:</label>
            <input type="number" id="Cantidad" name="Cantidad" value="{{ old('Cantidad', $producto->Cantidad) }}" required min="0">
            @error('Cantidad')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Descripción --}}
        <div>
            <label for="Descripcion">Descripción:</label>
            <textarea id="Descripcion" name="Descripcion" rows="4">{{ old('Descripcion', $producto->Descripcion) }}</textarea>
            @error('Descripcion')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Precio --}}
        <div>
            <label for="Precio">Precio:</label>
            <input type="number" id="Precio" name="Precio" value="{{ old('Precio', $producto->Precio) }}" step="0.01" required min="0">
            @error('Precio')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Tipo --}}
        <div>
            <label for="Tipo">Tipo:</label>
            <input type="text" id="Tipo" name="Tipo" value="{{ old('Tipo', $producto->Tipo) }}" required>
            @error('Tipo')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Código --}}
        <div>
            <label for="Codigo">Código:</label>
            <input type="text" id="Codigo" name="Codigo" value="{{ old('Codigo', $producto->Codigo) }}" required>
            @error('Codigo')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Id_Empresa (SELECT) --}}
        <div>
            <label for="Id_Empresa">Empresa:</label>
            <select id="Id_Empresa" name="Id_Empresa" required>
                <option value="">Seleccione una empresa</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->Id_Empresa }}" {{ old('Id_Empresa', $producto->Id_Empresa) == $empresa->Id_Empresa ? 'selected' : '' }}>
                        {{ $empresa->Nombre }}
                    </option>
                @endforeach
            </select>
            @error('Id_Empresa')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Id_Subcategoria (SELECT) --}}
        <div>
            <label for="Id_Subcategoria">Subcategoría:</label>
            <select id="Id_Subcategoria" name="Id_Subcategoria" required>
                <option value="">Seleccione una subcategoría</option>
                @foreach ($subcategorias as $subcategoria)
                    <option value="{{ $subcategoria->Id_Subcategoria }}" {{ old('Id_Subcategoria', $producto->Id_Subcategoria) == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                        {{ $subcategoria->Nombre }}
                    </option>
                @endforeach
            </select>
            @error('Id_Subcategoria')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Actualizar Producto</button>
    </form>

    <p class="back-link"><a href="{{ route('productos.index') }}">Volver a la lista de productos</a></p>
</body>
</html>