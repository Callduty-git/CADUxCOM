<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .container { max-width: 1100px; margin: 40px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px; text-align: left; }
        th { background: #f9fafb; }
        .search { margin-bottom: 16px; display:flex; gap:8px; }
        .badge { display:inline-block; padding:4px 8px; border-radius:999px; background:#e5e7eb; font-size:12px; }
        .price { font-weight:600; }
    </style>
    </head>
<body>
    <div class="page-container">
        <header class="main-header">
            <div class="left-section">
                <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
                <span class="logo-text">CADUxCOM</span>
            </div>
        </header>
        <main class="content">
        <x-admin.back-button />
    <div class="container">
        <h1>Productos</h1>
        <form method="GET" class="search">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre, marca o empresa" style="width:60%;padding:8px;">
            <button type="submit" style="padding:8px 12px;">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Empresa</th>
                    <th>Precio</th>
                    <th>Stock</th>
                    <th>Categoría</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $producto)
                    <tr>
                        <td>{{ $producto->Nombre }}</td>
                        <td>{{ $producto->empresa->Nombre ?? '—' }}</td>
                        <td class="price">${{ number_format($producto->Precio, 0, ',', '.') }}</td>
                        <td><span class="badge">{{ $producto->Cantidad }}</span></td>
                        <td>{{ $producto->subcategoria->categoria->Nombre ?? ($producto->subcategoria->Nombre ?? '—') }}</td>
                        <td>{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No hay productos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top:16px;">
            {{ $productos->links() }}
        </div>
    </div>
        </main>
    
    </div>
</body>
</html>