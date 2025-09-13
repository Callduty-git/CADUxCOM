<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
    <link rel="stylesheet" href="{{ asset('css/productos-index.css') }}">
</head>
<body>
    <h1>Productos Disponibles</h1>

    <a href="{{ route('productos.create') }}" class="btn-crear">Crear Nuevo Producto</a>

    @if ($productos->isEmpty())
        <p>No hay productos registrados.</p>
    @else
        <div class="contenedor-productos">
            @foreach ($productos as $producto)
                <div class="card">
                    <img src="{{ asset('storage/' . $producto->Foto) }}" alt="{{ $producto->Nombre }}">
                    <h3>{{ $producto->Nombre }}</h3>
                    <p class="marca">{{ $producto->Marca }}</p>
                    <p class="precio">
                        <span class="original">${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</span>
                        <span class="descuento">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                    </p>
                    <p class="vence">Vence: {{ \Carbon\Carbon::parse($producto->FechaVencimiento)->format('j/m/Y') }}</p>

                    <div class="acciones">
                        <a href="{{ route('empresa.productos.show', $producto->Id_Producto) }}">Ver Detalles</a>
                        <a href="{{ route('productos.edit', $producto->Id_Producto) }}">Editar</a>
                        <form action="{{ route('productos.destroy', $producto->Id_Producto) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">Eliminar</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>