<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Nombre -->
        <div>
            <x-input-label for="name" :value="__('Nombre')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text"
                name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email de acceso')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email"
                name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Rol -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Registrarse como')" />
            <select id="role" name="role" required
                class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <option value="usuario" {{ old('role') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                <option value="empresa" {{ old('role') == 'empresa' ? 'selected' : '' }}>Empresa</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- CAMPOS SOLO PARA EMPRESA -->
        <div id="empresa-fields" class="mt-4" style="display: none;">
            <!-- Foto -->
            <div class="mt-4">
                <x-input-label for="foto" :value="__('Foto de la empresa')" />
                <x-text-input id="foto" class="block mt-1 w-full" type="file"
                    name="foto" accept="image/*" />
                <x-input-error :messages="$errors->get('foto')" class="mt-2" />
            </div>

            <!-- Dirección -->
            <div class="mt-4">
                <x-input-label for="direccion" :value="__('Dirección')" />
                <x-text-input id="direccion" class="block mt-1 w-full" type="text"
                    name="direccion" :value="old('direccion')" />
                <x-input-error :messages="$errors->get('direccion')" class="mt-2" />
            </div>

            <!-- Municipio -->
            <div class="mt-4">
                <x-input-label for="municipio" :value="__('Municipio')" />
                <x-text-input id="municipio" class="block mt-1 w-full" type="text"
                    name="municipio" :value="old('municipio')" />
                <x-input-error :messages="$errors->get('municipio')" class="mt-2" />
            </div>

            <!-- Ubicación -->
            <div class="mt-4">
                <x-input-label for="ubicacion" :value="__('Ubicación (dirección o coordenadas)')" />
                <x-text-input id="ubicacion" class="block mt-1 w-full" type="text"
                    name="ubicacion" :value="old('ubicacion')" />
                <x-input-error :messages="$errors->get('ubicacion')" class="mt-2" />
            </div>

            <!-- Contacto -->
            <div class="mt-4">
                <x-input-label for="contacto" :value="__('Número de contacto')" />
                <x-text-input id="contacto" class="block mt-1 w-full" type="text"
                    name="contacto" :value="old('contacto')" />
                <x-input-error :messages="$errors->get('contacto')" class="mt-2" />
            </div>

            <!-- Email empresa -->
            <div class="mt-4">
                <x-input-label for="email_empresa" :value="__('Email de la empresa')" />
                <x-text-input id="email_empresa" class="block mt-1 w-full" type="email"
                    name="email_empresa" :value="old('email_empresa')" />
                <x-input-error :messages="$errors->get('email_empresa')" class="mt-2" />
            </div>

            <!-- NIT -->
            <div class="mt-4">
                <x-input-label for="nit" :value="__('NIT')" />
                <x-text-input id="nit" class="block mt-1 w-full" type="text"
                    name="nit" :value="old('nit')" />
                <x-input-error :messages="$errors->get('nit')" class="mt-2" />
            </div>

            <!-- Certificado Cámara de Comercio -->
            <div class="mt-4">
                <x-input-label for="certificado_camara_de_comercio" :value="__('Certificado Cámara de Comercio')" />
                <x-text-input id="certificado_camara_de_comercio" class="block mt-1 w-full" type="file"
                    name="certificado_camara_de_comercio" accept=".pdf,.jpg,.png" />
                <x-input-error :messages="$errors->get('certificado_camara_de_comercio')" class="mt-2" />
            </div>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Contraseña')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password"
                name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmar contraseña -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Botón -->
        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}">
                {{ __('¿Ya tienes una cuenta?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Registrarse') }}
            </x-primary-button>
        </div>
    </form>

    <!-- Script para mostrar campos adicionales si el rol es empresa -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roleSelect = document.getElementById('role');
            const empresaFields = document.getElementById('empresa-fields');

            function toggleEmpresaFields() {
                if (roleSelect.value === 'empresa') {
                    empresaFields.style.display = 'block';
                } else {
                    empresaFields.style.display = 'none';
                }
            }

            roleSelect.addEventListener('change', toggleEmpresaFields);
            toggleEmpresaFields(); // Inicial
        });
    </script>
</x-guest-layout>