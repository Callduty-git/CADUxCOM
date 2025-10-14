<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Empresa - CADUxCOM</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/empresa-auth-login.css') }}">
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <i class="fas fa-building"></i>
        </div>
        
        <div class="login-header">
            <h1 class="login-title">Login Empresa</h1>
            <p class="login-subtitle">Accede a tu panel de control</p>
        </div>
        @if(session('success'))
            <div style="background:#e6fffa;color:#065f46;border:1px solid #99f6e4;padding:10px 12px;border-radius:8px;margin-bottom:12px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('empresa.login') }}">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">Email:</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-input">
                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña:</label>
                <input id="password" type="password" name="password" required class="form-input">
                @error('password')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Recordarme</label>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Ingresar
            </button>
        </form>
    </div>
</body>
</html>
