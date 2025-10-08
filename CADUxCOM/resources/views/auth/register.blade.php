@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush

<x-guest-layout>
    <div class="register-container">
        <!-- Botón regresar en la parte superior -->
        <div class="back-button-container">
            <a href="{{ url('/') }}" class="back-button">
                <i class="fas fa-arrow-left"></i>
                <span>Regresar</span>
            </a>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="register-form">
            @csrf
            <input type="hidden" name="role" id="role" value="usuario" required>

            <!-- Header del formulario -->
            <div class="form-header">
                <div class="form-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
                <h2>Crear cuenta</h2>
                <p class="form-subtitle">Únete a nuestra comunidad</p>
            </div>

            <!-- Mostrar errores de validación -->
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

            <!-- Selector de Rol -->
            <div class="role-selector">
                <button type="button" id="btn-usuario" class="selected" onclick="switchToUsuario()">
                    <i class="fas fa-user"></i>
                    Usuario
                </button>
                <button type="button" id="btn-empresa" onclick="switchToEmpresa()">
                    <i class="fas fa-building"></i>
                    Empresa
                </button>
            </div>

            <!-- Información básica -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-info-circle"></i>
                    Información básica
                </h3>
                
                <div class="input-group">
                    <label for="name">
                        <i class="fas fa-user"></i>
                        Nombre de usuario
                    </label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" placeholder="Ingresa tu nombre">
                </div>

                <div class="input-group">
                    <label for="email">
                        <i class="fas fa-envelope"></i>
                        Email de acceso
                    </label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" autocomplete="username" placeholder="ejemplo@correo.com">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!-- Campos de empresa -->
            <div id="empresa-fields" class="form-section is-hidden">
                <h3 class="section-title">
                    <i class="fas fa-building"></i>
                    Información de empresa
                </h3>
                
                <div class="input-row">
                    <div class="input-group">
                        <label for="direccion">
                            <i class="fas fa-map-marker-alt"></i>
                            Dirección
                        </label>
                        <input id="direccion" type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Dirección de la empresa" required>
                        @error('direccion')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="municipio">
                            <i class="fas fa-city"></i>
                            Municipio
                        </label>
                        <input id="municipio" type="text" name="municipio" value="{{ old('municipio') }}" placeholder="Municipio" required>
                        @error('municipio')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="input-group">
                    <label for="ubicacion">
                        <i class="fas fa-location-arrow"></i>
                        Ubicación (dirección o coordenadas)
                    </label>
                    <input id="ubicacion" type="text" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="Ubicación específica">
                    @error('ubicacion')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="contacto">
                            <i class="fas fa-phone"></i>
                            Número de contacto
                        </label>
                        <input id="contacto" type="text" name="contacto" value="{{ old('contacto') }}" placeholder="Teléfono de contacto" required>
                        @error('contacto')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label for="email_empresa">
                            <i class="fas fa-envelope-open"></i>
                            Email de empresa
                        </label>
                        <input id="email_empresa" type="email" name="email_empresa" value="{{ old('email_empresa') }}" placeholder="contacto@empresa.com" required>
                        @error('email_empresa')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="input-group">
                    <label for="nit">
                        <i class="fas fa-id-card"></i>
                        NIT
                    </label>
                    <input id="nit" type="text" name="nit" value="{{ old('nit') }}" placeholder="Número de identificación tributaria" required>
                    @error('nit')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="files-section">
                    <h4 class="files-title">
                        <i class="fas fa-file-upload"></i>
                        Documentos requeridos
                    </h4>
                    <div class="files-container">
                        <div class="file-upload">
                            <label for="certificado">
                                <i class="fas fa-certificate"></i>
                                Certificado cámara y comercio
                                <span class="file-info">PDF, JPG o PNG</span>
                            </label>
                            <input id="certificado" type="file" name="certificado_camara_de_comercio" accept=".pdf,.jpg,.png" required>
                            @error('certificado_camara_de_comercio')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div id="certificado-preview" class="file-preview is-hidden">
                                <div class="preview-header">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Archivo seleccionado:</span>
                                    <button type="button" class="remove-file" onclick="removeCertificado()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="preview-content">
                                    <div id="certificado-info" class="file-info-display"></div>
                                    <div id="certificado-image" class="image-preview is-hidden"></div>
                                </div>
                            </div>
                        </div>

                        <div class="file-upload">
                            <label for="foto">
                                <i class="fas fa-image"></i>
                                Foto empresa
                                <span class="file-info">Imagen de la empresa</span>
                            </label>
                            <input id="foto" type="file" name="foto" accept="image/*" required>
                            @error('foto')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div id="foto-preview" class="file-preview is-hidden">
                                <div class="preview-header">
                                    <i class="fas fa-check-circle"></i>
                                    <span>Imagen seleccionada:</span>
                                    <button type="button" class="remove-file" onclick="removeFoto()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="preview-content">
                                    <div id="foto-info" class="file-info-display"></div>
                                    <div id="foto-image" class="image-preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contraseñas -->
            <div class="form-section">
                <h3 class="section-title">
                    <i class="fas fa-lock"></i>
                    Seguridad
                </h3>
                
                <div class="input-group">
                    <label for="password">
                        <i class="fas fa-key"></i>
                        Contraseña
                    </label>
                    <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Crea una contraseña segura">
                </div>

                <div class="input-group">
                    <label for="password_confirmation">
                        <i class="fas fa-check-circle"></i>
                        Confirmar contraseña
                    </label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirma tu contraseña">
                </div>
            </div>

            <!-- Botón -->
            <div class="button-container">
                <button type="submit" class="btn-register btn-register-submit">
                    <i class="fas fa-user-plus"></i>
                    Crear cuenta
                </button>
            </div>

            <!-- Link a login -->
            <div class="login-link">
                <span>¿Ya tienes una cuenta?</span>
                <a href="{{ route('login') }}">
                    <i class="fas fa-sign-in-alt"></i>
                    Inicia sesión aquí
                </a>
            </div>
        </form>
    </div>

    <script>
        // Función para cambiar a usuario
        function switchToUsuario() {
            console.log('Cambiando a usuario');
            const empresaFields = document.getElementById('empresa-fields');
            const roleInput = document.getElementById('role');
            const usuarioBtn = document.getElementById('btn-usuario');
            const empresaBtn = document.getElementById('btn-empresa');
            
            if (empresaFields) empresaFields.style.display = 'none';
            if (usuarioBtn) usuarioBtn.classList.add('selected');
            if (empresaBtn) empresaBtn.classList.remove('selected');
            if (roleInput) roleInput.value = 'usuario';
        }

        // Función para cambiar a empresa
        function switchToEmpresa() {
            console.log('Cambiando a empresa');
            const empresaFields = document.getElementById('empresa-fields');
            const roleInput = document.getElementById('role');
            const usuarioBtn = document.getElementById('btn-usuario');
            const empresaBtn = document.getElementById('btn-empresa');
            
            if (empresaFields) empresaFields.style.display = 'block';
            if (empresaBtn) empresaBtn.classList.add('selected');
            if (usuarioBtn) usuarioBtn.classList.remove('selected');
            if (roleInput) roleInput.value = 'empresa';
        }

        // Inicializar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando formulario');
            switchToUsuario();
        });

        // Funciones para vista previa de archivos
        function setupFilePreview(inputId, previewId, infoId, imageId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            const info = document.getElementById(infoId);
            const imageContainer = document.getElementById(imageId);

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    info.innerHTML = `
                        <div class="file-details">
                            <p><strong>Nombre:</strong> ${file.name}</p>
                            <p><strong>Tamaño:</strong> ${fileSize} MB</p>
                            <p><strong>Tipo:</strong> ${file.type}</p>
                        </div>
                    `;

                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imageContainer.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="preview-image">`;
                            imageContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imageContainer.innerHTML = `
                            <div class="file-icon">
                                <i class="fas fa-file-pdf" style="font-size: 48px; color: #dc3545;"></i>
                                <p>Archivo PDF seleccionado</p>
                            </div>
                        `;
                        imageContainer.style.display = 'block';
                    }

                    preview.style.display = 'block';
                }
            });
        }

        function removeCertificado() {
            const input = document.getElementById('certificado');
            const preview = document.getElementById('certificado-preview');
            input.value = '';
            preview.style.display = 'none';
        }

        function removeFoto() {
            const input = document.getElementById('foto');
            const preview = document.getElementById('foto-preview');
            input.value = '';
            preview.style.display = 'none';
        }

        // Configurar vistas previas cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            setupFilePreview('certificado', 'certificado-preview', 'certificado-info', 'certificado-image');
            setupFilePreview('foto', 'foto-preview', 'foto-info', 'foto-image');
        });
    </script>
</x-guest-layout>
