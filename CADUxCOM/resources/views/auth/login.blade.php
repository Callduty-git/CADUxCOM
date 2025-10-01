@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

<x-guest-layout>
    <div class="login-container">
        <!-- Botón regresar en la parte superior -->
        <div class="back-button-container">
            <a href="{{ url('/') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Regresar</span>
            </a>
        </div>

        <form method="POST" action="{{ route('login') }}" class="register-form login-form">
            @csrf

            <!-- Logo/Icono -->
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2>Iniciar sesión</h2>
                <p class="form-subtitle">Accede a tu cuenta</p>
            </div>

            <!-- Mostrar errores -->
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

            <!-- Email -->
            <div class="input-group">
                <label for="email">
                    <i class="fas fa-envelope"></i>
                    Correo electrónico
                </label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="ejemplo@correo.com">
            </div>

            <!-- Contraseña -->
            <div class="input-group">
                <label for="password">
                    <i class="fas fa-lock"></i>
                    Contraseña
                </label>
                <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña">
            </div>

            <!-- Recordar sesión -->
            <div class="remember-forgot">
                <label for="remember_me" class="remember-label">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>Recordarme</span>
                </label>
            </div>

            <!-- Contraseña olvidada -->
            @if (Route::has('password.request'))
                <div class="forgot-password">
                    <a href="{{ route('password.request') }}">
                        <i class="fas fa-question-circle"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            @endif

            <!-- Botón iniciar sesión -->
            <div class="button-container">
                <button type="submit" class="btn-register btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    Iniciar sesión
                </button>
            </div>

            <!-- Ir a registro -->
            <div class="register-link">
                <span>¿No tienes una cuenta?</span>
                <a href="{{ route('register') }}">
                    <i class="fas fa-user-plus"></i>
                    Regístrate aquí
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>

