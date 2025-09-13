@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
@endpush

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="register-form">
        @csrf
        <input type="hidden" name="role" id="role" value="usuario" required>

        <!-- Mostrar errores de validación -->
        @if ($errors->any())
            <div style="color: red; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Selector de Rol -->
        <div class="role-selector">
            <button type="button" id="btn-usuario" class="selected" type="button">Usuario</button>
            <button type="button" id="btn-empresa" type="button">Empresa</button>
        </div>

        <!-- Usuario -->
        <label for="name">Usuario:
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
        </label>

        <label for="email">Email acceso:
            <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username">
        </label>

        <!-- Campos de empresa -->
        <div id="empresa-fields" style="display: none;">
            <label for="direccion">Dirección:
                <input id="direccion" type="text" name="direccion" value="{{ old('direccion') }}">
            </label>

            <label for="municipio">Municipio:
                <input id="municipio" type="text" name="municipio" value="{{ old('municipio') }}">
            </label>

            <label for="ubicacion">Ubicación (dirección o coordenadas):
                <input id="ubicacion" type="text" name="ubicacion" value="{{ old('ubicacion') }}">
            </label>

            <label for="contacto">Número de contacto:
                <input id="contacto" type="text" name="contacto" value="{{ old('contacto') }}">
            </label>

            <label for="email_empresa">Email de empresa:
                <input id="email_empresa" type="email" name="email_empresa" value="{{ old('email_empresa') }}">
            </label>

            <label for="nit">NIT:
                <input id="nit" type="text" name="nit" value="{{ old('nit') }}">
            </label>

            <div class="files-container">
                <label for="certificado">Certificado cámara y comercio:
                    <input id="certificado" type="file" name="certificado_camara_de_comercio" accept=".pdf,.jpg,.png">
                </label>

                <label for="foto">Foto empresa:
                    <input id="foto" type="file" name="foto" accept="image/*">
                </label>
            </div>
        </div>

        <!-- Contraseñas -->
        <label for="password">Contraseña:
            <input id="password" type="password" name="password" required autocomplete="new-password">
        </label>

        <label for="password_confirmation">Confirmar contraseña:
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </label>

        <!-- Botón -->
        <div class="button-container">
            <button type="submit" class="btn-register">Registrarse</button>
        </div>
    </form>

    <script>
        const usuarioBtn = document.getElementById('btn-usuario');
        const empresaBtn = document.getElementById('btn-empresa');
        const empresaFields = document.getElementById('empresa-fields');
        const roleInput = document.getElementById('role');

        usuarioBtn.addEventListener('click', () => {
            empresaFields.style.display = 'none';
            usuarioBtn.classList.add('selected');
            empresaBtn.classList.remove('selected');
            roleInput.value = 'usuario';
        });

        empresaBtn.addEventListener('click', () => {
            empresaFields.style.display = 'block';
            empresaBtn.classList.add('selected');
            usuarioBtn.classList.remove('selected');
            roleInput.value = 'empresa';
        });

        document.querySelector('form').addEventListener('submit', () => {
            console.log("Rol enviado:", roleInput.value);
        });
    </script>
</x-guest-layout>
