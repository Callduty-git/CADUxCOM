<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios registrados</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .container { max-width: 1100px; margin: 40px auto; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #e5e7eb; padding: 10px; text-align: left; }
        th { background: #f9fafb; }
        .search { margin-bottom: 16px; }
        .badge { display:inline-block; padding:4px 8px; border-radius:999px; background:#e5e7eb; font-size:12px; }
    </style>
    </head>
<body>
    <div class="page-container">
        <x-header-pages />
        <main class="content">
        <x-admin.back-button />
    <div class="container">
        <h1>Usuarios</h1>
        <form method="GET" class="search">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nombre o correo" style="width:60%;padding:8px;">
            <button type="submit" style="padding:8px 12px;">Buscar</button>
        </form>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Registrado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge">{{ $user->role ?? 'usuario' }}</span></td>
                        <td>{{ $user->created_at?->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">
            {{ $users->links() }}
        </div>
    </div>
        </main>
        <x-footer />
    </div>
</body>
</html>