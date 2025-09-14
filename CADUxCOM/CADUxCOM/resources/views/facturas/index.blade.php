<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Facturas</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header-empresa.css') }}">
    
    <style>
        .header {
            border-bottom: 3px solid #006400;
        }
    </style>
</head>
<body>
    <!-- NUEVO HEADER -->
    <x-header-empresa />

    <div class="main-container">
        <aside class="sidebar">
            <nav class="nav-buttons">
                <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
                <a href="{{ route('empresa.productos.index') }}" class="btn">Productos</a>
                <a href="{{ route('empresa.facturas') }}" class="btn">Facturas</a>
                <form method="POST" action="{{ route('empresa.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn" aria-label="Cerrar sesión">Salir</button>
                </form>
            </nav>
        </aside>

        <main class="dashboard-panel">
            <div class="consola-panel">
                <h3 class="consola-title">Consola</h3>
                <ul class="log-lista">
                    @forelse ($logs as $log)
                        <li>
                            <span>[{{ \Carbon\Carbon::parse($log->hora)->format('d/m/Y H:i:s') }}]</span>
                            <span class="log-accion">[{{ $log->accion }}]</span>
                            <span class="log-descripcion">{{ $log->descripcion }}</span>
                        </li>
                    @empty
                        <p class="no-logs">No hay actividades registradas.</p>
                    @endforelse
                </ul>
            </div>
        </main>
    </div>
</body>
</html>
