<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>Nueva Contraseña - CADUxCOM</title>
    
    <!-- CSS de CADUxCOM -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #ffffff; /* Fondo blanco */
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Sobrescribir estilos del header para esta página */
        header {
            margin-bottom: 0 !important;
        }
        
        .main-header, .navbar {
            margin-bottom: 0 !important;
        }
        
        .page-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 0 !important; /* Eliminar padding del header */
        }
        
        .password-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin-top: 140px; /* Espacio para el header (80px + 60px) */
            background: #ffffff; /* Fondo blanco */
        }
        
        .password-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 500px;
            width: 100%;
            border: 1px solid #e9ecef;
            margin: 20px;
        }
        
        .password-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .password-icon {
            font-size: 64px;
            color: #49874E;
            margin-bottom: 20px;
        }
        
        .password-title {
            color: #49874E;
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }
        
        .password-subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin: 0;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            font-weight: 600;
            color: #49874E;
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .form-control {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e9ecef;
            border-radius: 12px;
            font-size: 16px;
            color: #495057;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        
        .form-control:focus {
            outline: none;
            border-color: #89CF6D;
            box-shadow: 0 0 0 3px rgba(137, 207, 109, 0.2);
        }
        
        .form-control.is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }
        
        .btn-change-password {
            width: 100%;
            background: linear-gradient(135deg, #49874E 0%, #89CF6D 100%);
            color: white;
            border: none;
            padding: 15px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(73, 135, 78, 0.3);
        }
        
        .btn-change-password:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(73, 135, 78, 0.4);
        }
        
        .btn-change-password:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .password-requirements {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
            border-left: 4px solid #49874E;
        }
        
        .password-requirements h5 {
            color: #49874E;
            margin: 0 0 10px 0;
            font-size: 16px;
        }
        
        .password-requirements ul {
            margin: 0;
            padding-left: 20px;
            color: #6c757d;
        }
        
        .password-requirements li {
            margin-bottom: 5px;
        }
        
        .password-strength {
            margin-top: 8px;
            font-size: 12px;
        }
        
        .password-strength.weak {
            color: #dc3545;
        }
        
        .password-strength.medium {
            color: #ffc107;
        }
        
        .password-strength.strong {
            color: #28a745;
        }
        
        .caduxcom-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .caduxcom-logo img {
            height: 60px;
            width: auto;
            margin-bottom: 10px;
        }
        
        .caduxcom-logo h2 {
            color: #49874E;
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }
        
        @media (max-width: 600px) {
            .password-container {
                margin-top: 120px; /* Menos espacio en móviles */
            }
            
            .password-card {
                padding: 30px 20px;
                margin: 10px;
            }
            
            .password-title {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- HEADER -->
        <x-header />
        
        <!-- CONTENIDO PRINCIPAL -->
        <div class="password-container">
        <div class="password-card">
            <div class="caduxcom-logo">
                <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM">
                <h2>CADUxCOM</h2>
            </div>
            
            <div class="password-header">
                <div class="password-icon">🔑</div>
                <h1 class="password-title">Nueva Contraseña</h1>
                <p class="password-subtitle">Crea una contraseña segura para tu cuenta</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.change') }}">
                @csrf
                
                <input type="hidden" name="email" value="{{ $email }}">
                <input type="hidden" name="token" value="{{ $token }}">
                
                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Ingresa tu nueva contraseña"
                        required
                        minlength="8"
                    >
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="password-strength" class="password-strength"></div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="Confirma tu nueva contraseña"
                        required
                        minlength="8"
                    >
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn-change-password">
                    <i class="fas fa-lock"></i> Cambiar Contraseña
                </button>
            </form>

            <div class="password-requirements">
                <h5><i class="fas fa-info-circle"></i> Requisitos de Contraseña</h5>
                <ul>
                    <li>Mínimo 8 caracteres</li>
                    <li>Debe contener al menos una letra mayúscula</li>
                    <li>Debe contener al menos una letra minúscula</li>
                    <li>Debe contener al menos un número</li>
                    <li>Se recomienda incluir caracteres especiales</li>
                </ul>
            </div>
        </div>
        </div>
        
        <!-- FOOTER -->
        <x-footer />
    </div>

    <script>
        // Verificación de fortaleza de contraseña
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('password-strength');
            
            if (password.length === 0) {
                strengthDiv.textContent = '';
                strengthDiv.className = 'password-strength';
                return;
            }
            
            let strength = 0;
            let feedback = [];
            
            // Longitud mínima
            if (password.length >= 8) {
                strength++;
            } else {
                feedback.push('Mínimo 8 caracteres');
            }
            
            // Contiene mayúscula
            if (/[A-Z]/.test(password)) {
                strength++;
            } else {
                feedback.push('Una mayúscula');
            }
            
            // Contiene minúscula
            if (/[a-z]/.test(password)) {
                strength++;
            } else {
                feedback.push('Una minúscula');
            }
            
            // Contiene número
            if (/[0-9]/.test(password)) {
                strength++;
            } else {
                feedback.push('Un número');
            }
            
            // Contiene carácter especial
            if (/[^A-Za-z0-9]/.test(password)) {
                strength++;
            }
            
            // Mostrar resultado
            if (strength < 2) {
                strengthDiv.textContent = 'Débil - Falta: ' + feedback.join(', ');
                strengthDiv.className = 'password-strength weak';
            } else if (strength < 4) {
                strengthDiv.textContent = 'Media - Falta: ' + feedback.join(', ');
                strengthDiv.className = 'password-strength medium';
            } else {
                strengthDiv.textContent = 'Fuerte ✓';
                strengthDiv.className = 'password-strength strong';
            }
        });

        // Verificación de coincidencia de contraseñas
        document.getElementById('password_confirmation').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmation = this.value;
            
            if (confirmation.length > 0) {
                if (password === confirmation) {
                    this.style.borderColor = '#28a745';
                } else {
                    this.style.borderColor = '#dc3545';
                }
            } else {
                this.style.borderColor = '#e9ecef';
            }
        });
    </script>
</body>
</html>
