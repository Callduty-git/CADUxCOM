<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
</head>
<body>
    <div class="container">
        <h2>Cambiar Contraseña</h2>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('empresa.password.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Contraseña Actual</label>
                <input type="password" name="current_password" required>
                @error('current_password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Nueva Contraseña</label>
                <input type="password" name="new_password" required>
                @error('new_password') <span class="error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Confirmar Nueva Contraseña</label>
                <input type="password" name="new_password_confirmation" required>
            </div>

            <button type="submit" class="btn">Actualizar Contraseña</button>
        </form>
    </div>
</body>
</html>
