<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto: {{ $producto->Nombre }}</title>
    <style>
        body { font-family: sans-serif; margin: 20px; line-height: 1.6; }
        .container { max-width: 800px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 8px; background-color: #f9f9f9; }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; margin-bottom: 20px; }
        p { margin-bottom: 10px; }
        strong { display: inline-block; width: 150px; } /* Para alinear etiquetas */
        .back-link, .edit-link { display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .edit-link { background-color: #ffc107; margin-left: 10px; }
        .back-link:hover { background-color: #0056b3; }
        .edit-link:hover { background-color: #e0a800; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalles del Producto</h1>

        <p><strong>Nombre:</strong> {{ $producto->Nombre }}</p>
        <p><strong>Marca:</strong> {{ $producto->Marca }}</p>
        <p><strong>Código:</strong> {{ $producto->Codigo }}</p>
        <p><strong>Precio:</strong> ${{ number_format($producto->Precio, 2) }}</p>
        <p><strong>Cantidad:</strong> {{ $producto->Cantidad }} {{ $producto->Tipo }}</p>
        <p><strong>Fecha Caducidad:</strong> {{ $producto->Fecha_Caducidad ? \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') : 'N/A' }}</p>
        <p><strong>Descripción:</strong> {{ $producto->Descripcion ?? 'Sin descripción' }}</p>

        {{-- Mostrar la empresa y subcategoría relacionadas (si las tienes cargadas) --}}
        {{-- Asegúrate de que en tu controlador, si usas eager loading (Producto::with('empresa', 'subcategoria')->find($id);), esto funcionará --}}
        <p><strong>Empresa:</strong> {{ $producto->empresa->Nombre ?? 'N/A' }}</p>
        <p><strong>Subcategoría:</strong> {{ $producto->subcategoria->Nombre ?? 'N/A' }}</p>

        <p><strong>Foto:</strong>
            @if ($producto->Foto)
                {{-- Si la foto es una URL --}}
                <img src="{{ $producto->Foto }}" alt="{{ $producto->Nombre }}" style="max-width: 200px; height: auto;">
                {{-- Si la foto se guarda en storage, necesitarías Asset::url('path/to/foto.jpg') --}}
            @else
                No disponible
            @endif
        </p>

        <a href="{{ route('productos.index') }}" class="back-link">Volver a la Lista</a>
        <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="edit-link">Editar Producto</a>
    </div>
</body>
</html>