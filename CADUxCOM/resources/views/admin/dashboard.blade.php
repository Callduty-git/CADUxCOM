<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container { max-width: 1100px; margin: 40px auto; padding: 20px; }
        .cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }
        .card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 16px; background: #fff; }
        .card h3 { margin: 0 0 8px; }
        .card p { color: #6b7280; }
        .card a { display: inline-block; margin-top: 10px; background: #111827; color: #fff; padding: 8px 12px; border-radius: 8px; text-decoration: none; }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />
        <main class="content">
        <x-admin.back-button />
    <div class="admin-container">
        <h1>Panel de Administrador</h1>
        <p>Gestiona empresas y usuarios registrados.</p>
        <div class="cards">
            <div class="card">
                <h3>Empresas pendientes</h3>
                <p>Revisa y aprueba solicitudes de empresa.</p>
                <a href="{{ route('admin.empresas.pending') }}">Ir a pendientes</a>
            </div>
            <div class="card">
                <h3>Empresas aprobadas</h3>
                <p>Consulta el historial de aprobaciones.</p>
                <a href="{{ route('admin.empresas.approved') }}">Ver aprobadas</a>
            </div>
            <div class="card">
                <h3>Empresas rechazadas</h3>
                <p>Administra rechazos y motivos.</p>
                <a href="{{ route('admin.empresas.rejected') }}">Ver rechazadas</a>
            </div>
            <div class="card">
                <h3>Usuarios</h3>
                <p>Listado de usuarios registrados.</p>
                <a href="{{ route('admin.users.index') }}">Ver usuarios</a>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" style="margin-top:24px;">
            @csrf
            <button type="submit" style="background:#ef4444;color:#fff;padding:10px 12px;border-radius:8px;border:none;cursor:pointer;">Cerrar sesión</button>
        </form>
    </div>
        </main>
        <x-footer />
    </div>
</body>
</html>