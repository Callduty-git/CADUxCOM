<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Usuario - Panel de Administrador</title>
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

        .form-container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
            color: #374151;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-group.checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-group.checkbox input {
            width: auto;
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

        .form-actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .error-list {
            margin: 0;
            padding-left: 20px;
        }

        .required {
            color: #ef4444;
        }

        .form-help {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .form-container {
                padding: 20px;
            }

            .form-actions {
                flex-direction: column;
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
                    <h1>Crear Nuevo Usuario</h1>
                    <p>Agrega un nuevo usuario al sistema CADUxCOM</p>
                </div>

                <!-- Errores de validación -->
                @if($errors->any())
                    <div class="alert alert-error">
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="error-list">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Formulario -->
                <div class="form-container">
                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Nombre Completo <span class="required">*</span></label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                            <div class="form-help">Nombre completo del usuario</div>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico <span class="required">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                            <div class="form-help">Debe ser un correo electrónico válido y único</div>
                        </div>

                        <div class="form-group">
                            <label for="password">Contraseña <span class="required">*</span></label>
                            <input type="password" id="password" name="password" required>
                            <div class="form-help">Mínimo 8 caracteres</div>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña <span class="required">*</span></label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required>
                            <div class="form-help">Debe coincidir con la contraseña anterior</div>
                        </div>

                        <div class="form-group">
                            <label for="role">Rol del Usuario <span class="required">*</span></label>
                            <select id="role" name="role" required>
                                <option value="">Selecciona un rol</option>
                                <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Usuario Regular</option>
                                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            <div class="form-help">Los administradores tienen acceso completo al panel de administración</div>
                        </div>

                        <div class="form-group checkbox">
                            <input type="checkbox" id="email_verified" name="email_verified" value="1" {{ old('email_verified') ? 'checked' : '' }}>
                            <label for="email_verified">Marcar email como verificado</label>
                        </div>
                        <div class="form-help" style="margin-left: 30px; margin-top: -15px;">
                            Si está marcado, el usuario no necesitará verificar su email
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                ✅ Crear Usuario
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                ❌ Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>


    <script>
        // Validación en tiempo real
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (password && confirmation && password !== confirmation) {
                this.setCustomValidity('Las contraseñas no coinciden');
            } else {
                this.setCustomValidity('');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            const confirmation = document.getElementById('password_confirmation');
            if (confirmation.value) {
                confirmation.dispatchEvent(new Event('input'));
            }
        });
    </script>
</body>
</html>