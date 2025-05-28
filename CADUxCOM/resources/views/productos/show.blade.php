<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto: {{ $producto->Nombre }}</title>
    <link rel="stylesheet" href="{{ asset('css/productos-show.css') }}">

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
        <p><strong>Empresa:</strong> {{ $producto->empresa->Nombre ?? 'N/A' }}</p>
        <p><strong>Subcategoría:</strong> {{ $producto->subcategoria->Nombre ?? 'N/A' }}</p>

        <p><strong>Foto:</strong>
            @if ($producto->Foto)
                <br>
                <img src="{{ asset('storage/' . $producto->Foto) }}" alt="{{ $producto->Nombre }}" class="product-image">
            @else
                No disponible
            @endif
        </p>

        <a href="{{ route('productos.index') }}" class="back-link">Volver a la Lista</a>
        <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="edit-link">Editar Producto</a>
    </div>
</body>
</html>