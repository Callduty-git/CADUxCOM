<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Empresa</title>
</head>
<body>
    <h1>Login Empresa</h1>

    <form method="POST" action="{{ route('empresa.login') }}">
        @csrf

        <label for="email">Email:</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
        @error('email')<div>{{ $message }}</div>@enderror

        <label for="password">Contraseña:</label>
        <input id="password" type="password" name="password" required>
        @error('password')<div>{{ $message }}</div>@enderror

        <label for="remember">
            <input type="checkbox" id="remember" name="remember"> Recordarme
        </label>

        <button type="submit">Ingresar</button>
    </form>
</body>
</html>
