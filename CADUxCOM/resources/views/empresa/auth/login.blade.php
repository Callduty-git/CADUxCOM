<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Empresa</title>
</head>
<body>
    <h1>Login Empresas</h1>
    <form method="POST" action="{{ route('empresa.login') }}">
        @csrf
        <label>Email:</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')<div>{{ $message }}</div>@enderror

        <label>Contraseña:</label>
        <input type="password" name="password" required>
        @error('password')<div>{{ $message }}</div>@enderror

        <label><input type="checkbox" name="remember"> Recordarme</label>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
