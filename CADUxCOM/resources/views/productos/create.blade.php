<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Nuevo Producto</title>
    <link rel="stylesheet" href="{{ asset('css/productos-create.css') }}">
</head>
<body>
    <h1>Crear Nuevo Producto</h1>

    <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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

        {{-- Campo Id_Empresa (SELECT DINÁMICO) --}}
        <div>
            <label for="Id_Empresa">Empresa:</label>
            <select id="Id_Empresa" name="Id_Empresa" required>
                <option value="">Seleccione una empresa</option>
                @foreach ($empresas as $empresa)
                    <option value="{{ $empresa->Id_Empresa }}" {{ old('Id_Empresa') == $empresa->Id_Empresa ? 'selected' : '' }}>
                        {{ $empresa->Nombre }}
                    </option>
                @endforeach
            </select>
            @error('Id_Empresa')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo Id_Subcategoria (SELECT DINÁMICO) --}}
        <div>
            <label for="Id_Subcategoria">Subcategoría:</label>
            <select id="Id_Subcategoria" name="Id_Subcategoria" required>
                <option value="">Seleccione una subcategoría</option>
                @foreach ($subcategorias as $subcategoria)
                    <option value="{{ $subcategoria->Id_Subcategoria }}" {{ old('Id_Subcategoria') == $subcategoria->Id_Subcategoria ? 'selected' : '' }}>
                        {{ $subcategoria->Nombre }}
                    </option>
                @endforeach
            </select>
            @error('Id_Subcategoria')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Campo para la Foto (Tipo FILE) --}}
        <div>
            <label for="Foto">Foto del Producto:</label>
            <input type="file" id="Foto" name="Foto" accept="image/"> {{-- 'accept="image/"' sugiere al navegador mostrar solo imágenes --}}
            @error('Foto')
                <div class="error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit">Guardar Producto</button>
    </form>

    <p class="back-link"><a href="{{ route('productos.index') }}">Volver a la lista de productos</a></p>
</body>
</html> 