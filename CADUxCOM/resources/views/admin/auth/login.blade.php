@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

<x-guest-layout>
    <div class="login-container">
        <x-admin.back-button href="{{ url('/') }}" label="Regresar" />

        <form method="POST" action="{{ route('admin.login.post') }}" class="register-form login-form">
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