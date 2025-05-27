<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Productos</title>
</head>
<body>
    <h1>Productos Disponibles</h1>

    <a href="{{ route('productos.create') }}">Crear Nuevo Producto</a>

    @if ($productos->isEmpty())
        <p>No hay productos registrados.</p>
    @else
        <ul>
            @foreach ($productos as $producto)
                <li>
                    {{ $producto->Nombre }} ({{ $producto->Marca }}) - Precio: ${{ $producto->Precio }}
                    {{-- Usar $producto->Id_Producto para las rutas --}}
                    <a href="{{ route('productos.show', $producto->Id_Producto) }}">Ver Detalles</a>
                    <a href="{{ route('productos.edit', $producto->Id_Producto) }}">Editar</a>
                    <form action="{{ route('productos.destroy', $producto->Id_Producto) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">Eliminar</button>
                    </form>
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>