@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
@endpush

<x-guest-layout>
    <form method="POST" action="{{ route('login') }}" class="register-form">
        @csrf

        <!-- Mostrar errores -->
        @if ($errors->any())
            <div style="color: red; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 style="text-align:center; color:#AA5FC7; margin-bottom: 20px;">Iniciar sesión</h2>

        <!-- Email -->
        <label>Correo electrónico:
            <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </label>

        <!-- Contraseña -->
        <label>Contraseña:
            <input type="password" name="password" required autocomplete="current-password">
        </label>

        <!-- ¿Olvidaste tu contraseña? -->
        <div style="text-align: center;">
            @if (Route::has('password.request'))
                <a style="color: #AA5FC7;" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Botón iniciar sesión -->
        <div class="button-container">
            <button class="btn-register">Iniciar sesión</button>
        </div>

        <!-- Ir a registro -->
        <div style="text-align: center; margin-top: 15px;">
            <a style="color: #AA5FC7;" href="{{ route('register') }}">¿No tienes una cuenta? Regístrate</a>
        </div>

        <!-- Botón regresar -->
        <div style="margin-top: 20px;">
            <a href="{{ url()->previous() }}" class="btn-register" style="background-color: #AA5FC7;">Regresar</a>
        </div>
    </form>
</x-guest-layout>

