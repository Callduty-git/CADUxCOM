@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .is-hidden { display: none !important; }
        /* Asegurar que los campos de empresa estén ocultos por defecto */
        #empresa-fields {
            display: none;
        }
        /* Mostrar cuando se selecciona empresa */
        #empresa-fields:not(.is-hidden) {
            display: block !important;
        }
    </style>
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

            <!-- Script inmediato para detectar estado del botón -->
            <script>
                // Ejecutar inmediatamente para detectar el estado del botón empresa
                (function() {
                    const empresaBtn = document.getElementById('btn-empresa');
                    if (empresaBtn && empresaBtn.classList.contains('selected')) {
                        console.log('Empresa button is selected on page load');
                        const empresaFields = document.getElementById('empresa-fields');
                        const roleInput = document.getElementById('role');
                        if (empresaFields) {
                            empresaFields.style.display = 'block';
                            empresaFields.classList.remove('is-hidden');
                        }
                        if (roleInput) {
                            roleInput.value = 'empresa';
                        }
                    }
                })();
            </script>

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
                <button type="button" id="btn-usuario" class="selected">
                    <i class="fas fa-user"></i>
                    Usuario
                </button>
                <button type="button" id="btn-empresa">
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
                        <input id="direccion" type="text" name="direccion" value="{{ old('direccion') }}" placeholder="Dirección de la empresa">
                    </div>

                    <div class="input-group">
                        <label for="municipio">
                            <i class="fas fa-city"></i>
                            Municipio
                        </label>
                        <select id="municipio" name="municipio" required>
                            <option value="">Selecciona un municipio</option>
                            <option value="Acevedo" {{ old('municipio') == 'Acevedo' ? 'selected' : '' }}>Acevedo</option>
                            <option value="Agrado" {{ old('municipio') == 'Agrado' ? 'selected' : '' }}>Agrado</option>
                            <option value="Aipe" {{ old('municipio') == 'Aipe' ? 'selected' : '' }}>Aipe</option>
                            <option value="Algeciras" {{ old('municipio') == 'Algeciras' ? 'selected' : '' }}>Algeciras</option>
                            <option value="Altamira" {{ old('municipio') == 'Altamira' ? 'selected' : '' }}>Altamira</option>
                            <option value="Baraya" {{ old('municipio') == 'Baraya' ? 'selected' : '' }}>Baraya</option>
                            <option value="Campoalegre" {{ old('municipio') == 'Campoalegre' ? 'selected' : '' }}>Campoalegre</option>
                            <option value="Colombia" {{ old('municipio') == 'Colombia' ? 'selected' : '' }}>Colombia</option>
                            <option value="Elías" {{ old('municipio') == 'Elías' ? 'selected' : '' }}>Elías</option>
                            <option value="Garzón" {{ old('municipio') == 'Garzón' ? 'selected' : '' }}>Garzón</option>
                            <option value="Gigante" {{ old('municipio') == 'Gigante' ? 'selected' : '' }}>Gigante</option>
                            <option value="Guadalupe" {{ old('municipio') == 'Guadalupe' ? 'selected' : '' }}>Guadalupe</option>
                            <option value="Hobo" {{ old('municipio') == 'Hobo' ? 'selected' : '' }}>Hobo</option>
                            <option value="Íquira" {{ old('municipio') == 'Íquira' ? 'selected' : '' }}>Íquira</option>
                            <option value="Isnos" {{ old('municipio') == 'Isnos' ? 'selected' : '' }}>Isnos</option>
                            <option value="La Argentina" {{ old('municipio') == 'La Argentina' ? 'selected' : '' }}>La Argentina</option>
                            <option value="La Plata" {{ old('municipio') == 'La Plata' ? 'selected' : '' }}>La Plata</option>
                            <option value="Nátaga" {{ old('municipio') == 'Nátaga' ? 'selected' : '' }}>Nátaga</option>
                            <option value="Neiva" {{ old('municipio') == 'Neiva' ? 'selected' : '' }}>Neiva</option>
                            <option value="Oporapa" {{ old('municipio') == 'Oporapa' ? 'selected' : '' }}>Oporapa</option>
                            <option value="Paicol" {{ old('municipio') == 'Paicol' ? 'selected' : '' }}>Paicol</option>
                            <option value="Palermo" {{ old('municipio') == 'Palermo' ? 'selected' : '' }}>Palermo</option>
                            <option value="Palestina" {{ old('municipio') == 'Palestina' ? 'selected' : '' }}>Palestina</option>
                            <option value="Pital" {{ old('municipio') == 'Pital' ? 'selected' : '' }}>Pital</option>
                            <option value="Pitalito" {{ old('municipio') == 'Pitalito' ? 'selected' : '' }}>Pitalito</option>
                            <option value="Rivera" {{ old('municipio') == 'Rivera' ? 'selected' : '' }}>Rivera</option>
                            <option value="Saladoblanco" {{ old('municipio') == 'Saladoblanco' ? 'selected' : '' }}>Saladoblanco</option>
                            <option value="San Agustín" {{ old('municipio') == 'San Agustín' ? 'selected' : '' }}>San Agustín</option>
                            <option value="Santa María" {{ old('municipio') == 'Santa María' ? 'selected' : '' }}>Santa María</option>
                            <option value="Suaza" {{ old('municipio') == 'Suaza' ? 'selected' : '' }}>Suaza</option>
                            <option value="Tarqui" {{ old('municipio') == 'Tarqui' ? 'selected' : '' }}>Tarqui</option>
                            <option value="Tello" {{ old('municipio') == 'Tello' ? 'selected' : '' }}>Tello</option>
                            <option value="Teruel" {{ old('municipio') == 'Teruel' ? 'selected' : '' }}>Teruel</option>
                            <option value="Tesalia" {{ old('municipio') == 'Tesalia' ? 'selected' : '' }}>Tesalia</option>
                            <option value="Timaná" {{ old('municipio') == 'Timaná' ? 'selected' : '' }}>Timaná</option>
                            <option value="Villavieja" {{ old('municipio') == 'Villavieja' ? 'selected' : '' }}>Villavieja</option>
                            <option value="Yaguará" {{ old('municipio') == 'Yaguará' ? 'selected' : '' }}>Yaguará</option>
                        </select>
                    </div>
                </div>

                <div class="input-group">
                    <label for="ubicacion">
                        <i class="fas fa-location-arrow"></i>
                        Ubicación (dirección o coordenadas)
                    </label>
                    <input id="ubicacion" type="text" name="ubicacion" value="{{ old('ubicacion') }}" placeholder="Ubicación específica">
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="contacto">
                            <i class="fas fa-phone"></i>
                            Número de contacto
                        </label>
                        <input id="contacto" type="text" name="contacto" value="{{ old('contacto') }}" placeholder="Teléfono de contacto">
                    </div>

                    <div class="input-group">
                        <label for="email_empresa">
                            <i class="fas fa-envelope-open"></i>
                            Email de empresa
                        </label>
                        <input id="email_empresa" type="email" name="email_empresa" value="{{ old('email_empresa') }}" placeholder="contacto@empresa.com">
                    </div>
                </div>

                <div class="input-group">
                    <label for="nit">
                        <i class="fas fa-id-card"></i>
                        NIT
                    </label>
                    <input id="nit" type="text" name="nit" value="{{ old('nit') }}" placeholder="Número de identificación tributaria">
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
                            <input id="certificado" type="file" name="certificado_camara_de_comercio" accept=".pdf,.jpg,.jpeg,.png">
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
                            <input id="foto" type="file" name="foto" accept="image/*">
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

                <!-- Términos y condiciones (solo empresas) -->
                <div id="terms-group" class="form-section" style="margin-top: 12px;">
                    <h3 class="section-title">
                        <i class="fas fa-file-contract"></i>
                        Términos y condiciones
                    </h3>
                    <div class="input-group">
                        <label for="terms" style="display:flex; align-items:center; gap:8px;">
                            <input id="terms" type="checkbox" name="terms" value="1" {{ session('terms_read') ? '' : 'disabled' }}>
                            He leído y acepto los
                            <a href="{{ route('terms') }}" class="terms-link" style="text-decoration: underline;">
                                Términos y Condiciones
                            </a>
                        </label>
                        <p class="helper-text" style="margin-top:8px; color:#666;">
                            Debes abrir y leer los términos. Al final de la página encontrarás un botón para habilitar esta casilla.
                        </p>
                        <p id="terms-status" class="helper-text text-success is-hidden" style="margin-top:6px;">
                            Términos aceptados. Ya puedes marcar la casilla.
                        </p>
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
        const usuarioBtn = document.getElementById('btn-usuario');
        const empresaBtn = document.getElementById('btn-empresa');
        const empresaFields = document.getElementById('empresa-fields');
        const termsGroup = document.getElementById('terms-group');
        const roleInput = document.getElementById('role');
        const termsCheckbox = document.getElementById('terms');
        const termsStatus = document.getElementById('terms-status');

        // Función para cambiar a modo empresa
        function switchToEmpresa() {
            console.log('Switching to empresa mode');
            empresaFields.classList.remove('is-hidden');
            if (termsGroup) termsGroup.classList.remove('is-hidden');
            empresaBtn.classList.add('selected');
            usuarioBtn.classList.remove('selected');
            roleInput.value = 'empresa';
            console.log('Role set to:', roleInput.value);
            saveFormData();
        }

        // Función para cambiar a modo usuario
        function switchToUsuario() {
            console.log('Switching to usuario mode');
            empresaFields.classList.add('is-hidden');
            if (termsGroup) termsGroup.classList.add('is-hidden');
            usuarioBtn.classList.add('selected');
            empresaBtn.classList.remove('selected');
            roleInput.value = 'usuario';
            console.log('Role set to:', roleInput.value);
            saveFormData();
        }

        // Autosave: guardar y restaurar datos del formulario (excepto archivos y contraseñas)
        const autosaveFields = ['role','name','email','direccion','municipio','ubicacion','contacto','email_empresa','nit'];

        function saveFormData() {
            const data = {};
            autosaveFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) data[id] = el.value;
            });
            try { localStorage.setItem('registerFormData', JSON.stringify(data)); } catch (_) {}
        }

        function restoreFormData() {
            try {
                const raw = localStorage.getItem('registerFormData');
                if (!raw) return;
                const data = JSON.parse(raw);
                Object.entries(data).forEach(([id, val]) => {
                    const el = document.getElementById(id);
                    if (el && typeof val === 'string') el.value = val;
                });
            } catch (_) {}
        }

        // Estado inicial en base al rol seleccionado + restauración
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, initializing form');
            
            // Restaurar datos previamente guardados
            restoreFormData();

            // Verificar si hay datos guardados que indiquen que se seleccionó empresa
            const savedRole = localStorage.getItem('registerFormData');
            let isEmpresaSelected = false;
            
            if (savedRole) {
                try {
                    const data = JSON.parse(savedRole);
                    isEmpresaSelected = data.role === 'empresa';
                    console.log('Found saved data:', data);
                } catch (_) {}
            }

            console.log('Initial role value:', roleInput.value);
            console.log('Is empresa selected from localStorage:', isEmpresaSelected);
            
            // Determinar el modo inicial
            if (roleInput.value === 'empresa' || isEmpresaSelected) {
                switchToEmpresa();
            } else {
                switchToUsuario();
            }

            // Forzar actualización visual después de un pequeño delay
            setTimeout(() => {
                if (roleInput.value === 'empresa') {
                    empresaFields.style.display = 'block';
                    empresaFields.classList.remove('is-hidden');
                } else {
                    empresaFields.style.display = 'none';
                    empresaFields.classList.add('is-hidden');
                }
            }, 100);

            // Si ya aceptó términos en otra pestaña, habilitar la casilla
            try {
                if (termsCheckbox && localStorage.getItem('terms_read') === '1') {
                    termsCheckbox.removeAttribute('disabled');
                    if (termsStatus) termsStatus.classList.remove('is-hidden');
                }
            } catch (_) {}
        });

        // Event listeners para los botones
        usuarioBtn.addEventListener('click', switchToUsuario);
        empresaBtn.addEventListener('click', switchToEmpresa);

        // Guardar cambios conforme se escribe
        autosaveFields.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.addEventListener('input', saveFormData);
            if (el) el.addEventListener('change', saveFormData);
        });

        document.querySelector('form').addEventListener('submit', () => {
            // Mantener datos por si hay validaciones del servidor; si deseas limpiar al éxito, podemos hacerlo luego.
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
                    // Mostrar información del archivo
                    const fileSize = (file.size / 1024 / 1024).toFixed(2);
                    info.innerHTML = `
                        <div class="file-details">
                            <p><strong>Nombre:</strong> ${file.name}</p>
                            <p><strong>Tamaño:</strong> ${fileSize} MB</p>
                            <p><strong>Tipo:</strong> ${file.type}</p>
                        </div>
                    `;

                    // Si es una imagen, mostrar vista previa
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imageContainer.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="preview-image">`;
                            if (imageContainer) {
                                imageContainer.classList.remove('is-hidden');
                                imageContainer.style.display = 'block';
                            }
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // Para PDFs u otros archivos
                        imageContainer.innerHTML = `
                            <div class="file-icon">
                                <i class="fas fa-file-pdf" style="font-size: 48px; color: #dc3545;"></i>
                                <p>Archivo PDF seleccionado</p>
                            </div>
                        `;
                        if (imageContainer) {
                            imageContainer.classList.remove('is-hidden');
                            imageContainer.style.display = 'block';
                        }
                    }
                    // Mostrar contenedor de vista previa
                    preview.classList.remove('is-hidden');
                    preview.style.display = 'block';
                }
            });
        }

        // Funciones para remover archivos
        function removeCertificado() {
            const input = document.getElementById('certificado');
            const preview = document.getElementById('certificado-preview');
            const info = document.getElementById('certificado-info');
            const imageContainer = document.getElementById('certificado-image');
            input.value = '';
            preview.classList.add('is-hidden');
            preview.style.display = 'none';
            if (info) info.innerHTML = '';
            if (imageContainer) {
                imageContainer.classList.add('is-hidden');
                imageContainer.style.display = 'none';
                imageContainer.innerHTML = '';
            }
        }

        function removeFoto() {
            const input = document.getElementById('foto');
            const preview = document.getElementById('foto-preview');
            const info = document.getElementById('foto-info');
            const imageContainer = document.getElementById('foto-image');
            input.value = '';
            preview.classList.add('is-hidden');
            preview.style.display = 'none';
            if (info) info.innerHTML = '';
            if (imageContainer) {
                imageContainer.classList.add('is-hidden');
                imageContainer.style.display = 'none';
                imageContainer.innerHTML = '';
            }
        }

        // Configurar vistas previas
        setupFilePreview('certificado', 'certificado-preview', 'certificado-info', 'certificado-image');
        setupFilePreview('foto', 'foto-preview', 'foto-info', 'foto-image');
    </script>
    
    <!-- Modal de Términos y Condiciones -->
    <div id="terms-modal" class="modal-overlay is-hidden" aria-hidden="true">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-file-contract"></i> Términos y Condiciones</h3>
                <button type="button" class="modal-close" id="terms-modal-close" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <iframe id="terms-iframe" src="{{ route('terms', ['embed' => 1]) }}" title="Términos y Condiciones" class="modal-iframe"></iframe>
            </div>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" id="modal-accept-btn">
                    He leído y acepto
                </button>
                <button type="button" class="btn btn-primary" id="modal-cancel-btn">Cerrar</button>
            </div>
        </div>
    </div>

    <script>
        // Modal Términos
        const termsLink = document.querySelector('.terms-link');
        const termsModal = document.getElementById('terms-modal');
        const modalAcceptBtn = document.getElementById('modal-accept-btn');
        const modalCloseBtn = document.getElementById('terms-modal-close');
        const modalCancelBtn = document.getElementById('modal-cancel-btn');

        function openTermsModal(e) {
            if (e) e.preventDefault();
            termsModal.classList.remove('is-hidden');
            termsModal.setAttribute('aria-hidden', 'false');
            // Bloquear scroll del body mientras el modal está abierto
            document.body.style.overflow = 'hidden';
            // Botón habilitado inmediatamente
            if (modalAcceptBtn) modalAcceptBtn.disabled = false;
        }

        function closeTermsModal() {
            termsModal.classList.add('is-hidden');
            termsModal.setAttribute('aria-hidden', 'true');
            // Restaurar scroll del body
            document.body.style.overflow = '';
        }

        if (termsLink) termsLink.addEventListener('click', openTermsModal);
        if (modalCloseBtn) modalCloseBtn.addEventListener('click', closeTermsModal);
        if (modalCancelBtn) modalCancelBtn.addEventListener('click', closeTermsModal);

        // Aceptar desde el modal: habilita casilla y persiste aceptación
        if (modalAcceptBtn) {
            modalAcceptBtn.addEventListener('click', () => {
                try { localStorage.setItem('terms_read', '1'); } catch (_) {}
                if (termsCheckbox) termsCheckbox.removeAttribute('disabled');
                if (termsStatus) termsStatus.classList.remove('is-hidden');
                closeTermsModal();
            });
        }

        // Cerrar con tecla Escape
        window.addEventListener('keydown', (ev) => {
            if (ev.key === 'Escape' && !termsModal.classList.contains('is-hidden')) {
                closeTermsModal();
            }
        });

        // Cerrar al hacer clic fuera del contenido
        termsModal.addEventListener('click', (ev) => {
            if (ev.target === termsModal) {
                closeTermsModal();
            }
        });

        // Escuchar mensaje desde iframe de términos cuando se llega al final
        window.addEventListener('message', (event) => {
            try {
                // Habilitar sin comprobar origen estrictamente, validando solo el tipo de mensaje
                if (event.data && event.data.type === 'terms-scroll-bottom') {
                    modalAcceptBtn.disabled = false;
                }
            } catch (_) {}
        });

        // Escucha cambios de localStorage desde otra pestaña (términos)
        window.addEventListener('storage', (e) => {
            if (e.key === 'terms_read' && e.newValue === '1') {
                if (termsCheckbox) termsCheckbox.removeAttribute('disabled');
                if (termsStatus) termsStatus.classList.remove('is-hidden');
            }
        });
    </script>
</x-guest-layout>
