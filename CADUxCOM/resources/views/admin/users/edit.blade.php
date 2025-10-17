<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario - {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .admin-header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .admin-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #374151;
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-select {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }

        .form-select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 10px;
        }

        .form-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .form-checkbox label {
            margin: 0;
            font-weight: normal;
            cursor: pointer;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 5px;
            display: block;
        }

        .form-help {
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .actions-bar {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .password-section {
            border-top: 2px solid #e5e7eb;
            padding-top: 25px;
            margin-top: 25px;
        }

        .password-section h3 {
            margin: 0 0 20px 0;
            color: #374151;
            font-size: 1.2rem;
        }

        .password-help {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .password-help h4 {
            margin: 0 0 10px 0;
            color: #374151;
            font-size: 1rem;
        }

        .password-help ul {
            margin: 0;
            padding-left: 20px;
            color: #6b7280;
        }

        .password-match {
            margin-top: 5px;
            font-size: 0.875rem;
        }

        .password-match.valid {
            color: #059669;
        }

        .password-match.invalid {
            color: #ef4444;
        }

        .user-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            border-left: 4px solid #667eea;
        }

        .user-info h3 {
            margin: 0 0 15px 0;
            color: #374151;
        }

        .user-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .user-info-item {
            display: flex;
            flex-direction: column;
        }

        .user-info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 5px;
        }

        .user-info-value {
            color: #374151;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-admin {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-user {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-verified {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-unverified {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .actions-bar {
                flex-direction: column;
            }

            .user-info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <header class="main-header">
            <div class="left-section">
                <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
                <span class="logo-text">CADUxCOM</span>
            </div>
        </header>
        <main class="content">
            <x-admin.back-button />
            
            <div class="admin-container">
                <!-- Header -->
                <div class="admin-header">
                    <h1>Editar Usuario</h1>
                    <p>Modificar información de {{ $user->name }}</p>
                </div>

                <!-- Alertas -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Información actual del usuario -->
                <div class="user-info">
                    <h3>📋 Información Actual</h3>
                    <div class="user-info-grid">
                        <div class="user-info-item">
                            <span class="user-info-label">ID del Usuario:</span>
                            <span class="user-info-value">#{{ $user->id }}</span>
                        </div>
                        <div class="user-info-item">
                            <span class="user-info-label">Fecha de Registro:</span>
                            <span class="user-info-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="user-info-item">
                            <span class="user-info-label">Rol Actual:</span>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                            </span>
                        </div>
                        <div class="user-info-item">
                            <span class="user-info-label">Estado del Email:</span>
                            <span class="badge {{ $user->email_verified_at ? 'badge-verified' : 'badge-unverified' }}">
                                {{ $user->email_verified_at ? 'Verificado' : 'No Verificado' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Formulario de edición -->
                <div class="form-card">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" id="editUserForm">
                        @csrf
                        @method('PUT')

                        <!-- Información básica -->
                        <div class="form-group">
                            <label for="name" class="form-label">Nombre Completo *</label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-input" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            @error('name')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">Correo Electrónico *</label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   class="form-input" 
                                   value="{{ old('email', $user->email) }}" 
                                   required>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                Si cambias el email, el usuario deberá verificarlo nuevamente.
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="role" class="form-label">Rol del Usuario *</label>
                            <select id="role" name="role" class="form-select" required>
                                <option value="user" {{ old('role', $user->role) === 'user' ? 'selected' : '' }}>
                                    👤 Usuario Regular
                                </option>
                                <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>
                                    👑 Administrador
                                </option>
                            </select>
                            @error('role')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                Los administradores tienen acceso completo al panel de administración.
                            </div>
                        </div>

                        <!-- Estado de verificación de email -->
                        <div class="form-group">
                            <label class="form-label">Estado de Verificación del Email</label>
                            <div class="form-checkbox">
                                <input type="checkbox" 
                                       id="email_verified" 
                                       name="email_verified" 
                                       value="1"
                                       {{ old('email_verified', $user->email_verified_at ? '1' : '0') === '1' ? 'checked' : '' }}>
                                <label for="email_verified">Email verificado</label>
                            </div>
                            @error('email_verified')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            <div class="form-help">
                                Marca esta casilla si quieres que el email aparezca como verificado.
                            </div>
                        </div>

                        <!-- Sección de cambio de contraseña -->
                        <div class="password-section">
                            <h3>🔒 Cambiar Contraseña (Opcional)</h3>
                            
                            <div class="password-help">
                                <h4>ℹ️ Información importante:</h4>
                                <ul>
                                    <li>Deja estos campos vacíos si no quieres cambiar la contraseña</li>
                                    <li>La contraseña debe tener al menos 8 caracteres</li>
                                    <li>Se recomienda usar una combinación de letras, números y símbolos</li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Nueva Contraseña</label>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-input" 
                                       minlength="8">
                                @error('password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       class="form-input" 
                                       minlength="8">
                                <div id="passwordMatch" class="password-match"></div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="actions-bar">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                                ← Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                💾 Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>


    <script>
        // Validación de contraseñas en tiempo real
        const password = document.getElementById('password');
        const passwordConfirmation = document.getElementById('password_confirmation');
        const passwordMatch = document.getElementById('passwordMatch');

        function checkPasswordMatch() {
            const pass = password.value;
            const confirm = passwordConfirmation.value;

            if (pass === '' && confirm === '') {
                passwordMatch.textContent = '';
                passwordMatch.className = 'password-match';
                return;
            }

            if (pass === confirm && pass.length >= 8) {
                passwordMatch.textContent = '✅ Las contraseñas coinciden';
                passwordMatch.className = 'password-match valid';
            } else if (pass === confirm && pass.length < 8) {
                passwordMatch.textContent = '⚠️ Las contraseñas coinciden pero deben tener al menos 8 caracteres';
                passwordMatch.className = 'password-match invalid';
            } else {
                passwordMatch.textContent = '❌ Las contraseñas no coinciden';
                passwordMatch.className = 'password-match invalid';
            }
        }

        password.addEventListener('input', checkPasswordMatch);
        passwordConfirmation.addEventListener('input', checkPasswordMatch);

        // Validación del formulario antes del envío
        document.getElementById('editUserForm').addEventListener('submit', function(e) {
            const pass = password.value;
            const confirm = passwordConfirmation.value;

            // Si se ingresó una contraseña, validar que coincidan y tengan al menos 8 caracteres
            if (pass !== '' || confirm !== '') {
                if (pass !== confirm) {
                    e.preventDefault();
                    alert('Las contraseñas no coinciden. Por favor, verifica e intenta nuevamente.');
                    passwordConfirmation.focus();
                    return false;
                }

                if (pass.length < 8) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres.');
                    password.focus();
                    return false;
                }
            }

            // Confirmación antes de guardar cambios importantes
            const currentRole = '{{ $user->role }}';
            const newRole = document.getElementById('role').value;
            
            if (currentRole !== newRole) {
                if (!confirm('¿Estás seguro de cambiar el rol de este usuario? Esto puede afectar sus permisos de acceso.')) {
                    e.preventDefault();
                    return false;
                }
            }

            return true;
        });

        // Auto-focus en el primer campo
        document.getElementById('name').focus();
    </script>
</body>
</html>