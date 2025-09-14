<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
</head>
<body>

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <img src="{{ asset('images/logo-caduxcom.png') }}" alt="Logo" class="logo">
        <h1>CADUxCOM</h1>
    </div>
    <div class="header-right">
        <img src="{{ asset('images/profile.png') }}" alt="Perfil" class="profile">
    </div>
</div>

<!-- CONTENEDOR PRINCIPAL -->
<div class="main-container">

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <nav class="nav-buttons">
            <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
            <a href="{{ route('empresa.productos.index') }}" class="btn">Productos</a>
            <a href="{{ route('empresa.facturas') }}" class="btn">Facturas</a>
            <form method="POST" action="{{ route('empresa.logout') }}" style="margin-top: 10px;">
                @csrf
                <button type="submit" class="btn">Salir</button>
            </form>
        </nav>
    </aside>

    <!-- CONTENIDO CENTRAL -->
    <main class="dashboard-panel">
    <h2 class="editar-titulo">EDITAR</h2>

    <div class="form-box">
        <form action="{{ route('productos.update', $producto->Id_Producto) }}" method="POST" enctype="multipart/form-data" class="edit-form">
            @csrf
            @method('PUT')

            <div class="form-left">
                <label for="Nombre">Nombre:</label>
                <input type="text" name="Nombre" value="{{ old('Nombre', $producto->Nombre) }}" required>

                <label for="Marca">Marca:</label>
                <input type="text" name="Marca" value="{{ old('Marca', $producto->Marca) }}" required>

                <label for="Codigo">Código:</label>
                <input type="text" name="Codigo" value="{{ old('Codigo', $producto->Codigo) }}" required>

                <label for="PrecioOriginal">Precio Original:</label>
                <div class="input-icon">
                    <span>$</span>
                    <input type="number" step="0.01" name="PrecioOriginal" value="{{ old('PrecioOriginal', $producto->PrecioOriginal) }}" required>
                </div>

                <label for="Precio">Precio:</label>
                <div class="input-icon">
                    <span>$</span>
                    <input type="number" step="0.01" name="Precio" value="{{ old('Precio', $producto->Precio) }}" required>
                </div>

                <label for="Cantidad">Cantidad:</label>
                <input type="number" name="Cantidad" value="{{ old('Cantidad', $producto->Cantidad) }}" required>

                <label for="Tipo">Tipo:</label>
                <input type="text" name="Tipo" value="{{ old('Tipo', $producto->Tipo) }}" required>

                <label for="Fecha_Caducidad">Fecha de caducidad:</label>
                <input type="date" name="Fecha_Caducidad" value="{{ old('Fecha_Caducidad', $producto->Fecha_Caducidad) }}">

                <label for="Descripcion">Descripción:</label>
                <textarea name="Descripcion">{{ old('Descripcion', $producto->Descripcion) }}</textarea>

                <label for="Id_Empresa">Empresa:</label>
                <select name="Id_Empresa" required>
                    <option value="">Seleccionar empresa</option>
                    @foreach ($empresas as $empresa)
                        <option value="{{ $empresa->Id_Empresa }}" {{ $producto->Id_Empresa == $empresa->Id_Empresa ? 'selected' : '' }}>
                            {{ $empresa->Nombre }}
                        </option>
                    @endforeach
                </select>

                <label for="Id_Subcategoria">Subcategoría:</label>
                <select name="Id_Subcategoria" required>
                    <option value="">Seleccionar subcategoría</option>
                    @foreach ($subcategorias as $sub)
                        <option value="{{ $sub->Id_Subcategoria }}" {{ $producto->Id_Subcategoria == $sub->Id_Subcategoria ? 'selected' : '' }}>
                            {{ $sub->Nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-right">
                @if ($producto->Foto)
                    <img src="{{ asset('storage/' . $producto->Foto) }}" class="img-preview" alt="Foto actual">
                @endif

                <label for="Foto" class="btn-foto">Actualizar Foto</label>
                <input type="file" name="Foto" id="Foto" style="display: none;">

                <button type="submit" class="btn-guardar">Guardar cambios</button>
            </div>
        </form>
    </div>
</main>

</div>

</body>
</html>
    