@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .back-button-container {
            margin-bottom: 20px;
        }
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.2s;
        }
        .back-button:hover {
            color: #374151;
        }
    </style>
@endpush

<x-guest-layout>
    <div class="login-container">
        <div class="back-button-container">
            <a href="{{ url('/') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Regresar
            </a>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" class="register-form login-form">
            @csrf

            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h2>Acceso Administrador</h2>
                <p class="form-subtitle">Solo para cuentas con rol administrador</p>
            </div>

            @if ($errors->any())
                <div class="error-container">
                    <i class="fas fa-exclamation-triangle"></i>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="input-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Correo electrónico
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="admin@correo.com">
            </div>

            <div class="input-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <input id="password" type="password" name="password" required placeholder="••••••••">
            </div>

            <div class="checkbox-group">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Recordarme</label>
            </div>

            <div class="button-container">
                <button type="submit" class="btn-register btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Entrar al panel
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>