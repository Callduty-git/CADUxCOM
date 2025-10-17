<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Cuenta - Centro de Ayuda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/header-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .account-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 2rem;
        }

        .account-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .account-header h1 {
            color: #49874E;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .account-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .account-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .account-option {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .account-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #89CF6D, #49874E);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .account-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(137, 207, 109, 0.3);
            border-color: #89CF6D;
        }

        .account-option:hover::before {
            transform: scaleX(1);
        }

        .account-icon {
            width: 80px;
            height: 80px;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .account-option:hover .account-icon {
            transform: scale(1.1);
        }

        .account-option h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #49874E;
            transition: color 0.3s ease;
        }

        .account-option:hover h3 {
            color: #89CF6D;
        }

        .account-option p {
            color: #666;
            line-height: 1.6;
            margin: 0;
            transition: color 0.3s ease;
        }

        .account-option:hover p {
            color: #555;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .info-section h3 {
            color: #49874E;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .info-list {
            list-style: none;
            padding: 0;
        }

        .info-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #e9ecef;
            color: #666;
        }

        .info-list li:last-child {
            border-bottom: none;
        }

        .info-list li::before {
            content: '✓';
            color: #89CF6D;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #49874E;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #89CF6D;
        }

        .back-link::before {
            content: '←';
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        @media (max-width: 768px) {
            .account-container {
                padding: 1rem;
            }
            
            .account-header h1 {
                font-size: 2rem;
            }
            
            .account-options {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .account-option {
                padding: 1.5rem;
            }
            
            .account-icon {
                width: 60px;
                height: 60px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />

        <div class="help-container">
            <div class="account-container">
                <a href="{{ route('help') }}" class="back-link">Volver al Centro de Ayuda</a>
                
                <div class="account-header">
                    <h1>Mi Cuenta</h1>
                    <p>Gestiona tu perfil y configuraciones de cuenta</p>
                </div>

                @auth('web')
                    <!-- Usuario autenticado -->
                    <div class="account-options">
                        <a href="{{ route('profile.edit') }}" class="account-option">
                            <img src="{{ asset('images/icon-user.png') }}" alt="Perfil Usuario" class="account-icon">
                            <h3>Mi Perfil de Usuario</h3>
                            <p>Edita tu información personal, cambia tu contraseña y gestiona tus preferencias</p>
                        </a>
                        <a href="{{ route('dashboard') }}" class="account-option">
                            <img src="{{ asset('images/carrito-de-compras.png') }}" alt="Dashboard" class="account-icon">
                            <h3>Mi Dashboard</h3>
                            <p>Ve tu actividad reciente, pedidos y estadísticas de tu cuenta</p>
                        </a>
                    </div>

                    <div class="info-section">
                        <h3>¿Qué puedes hacer en tu perfil de usuario?</h3>
                        <ul class="info-list">
                            <li>Actualizar tu información personal (nombre, email, teléfono)</li>
                            <li>Cambiar tu contraseña de acceso</li>
                            <li>Gestionar tus direcciones de contacto (para coordinación con empresas)</li>
                            <li>Configurar tus preferencias de notificaciones</li>
                            <li>Ver tu historial de compras</li>
                            <li>Gestionar tu lista de deseos</li>
                        </ul>
                    </div>
                @endauth

                @auth('empresa')
                    <!-- Empresa autenticada -->
                    <div class="account-options">
                        <a href="{{ route('empresa.perfil.edit') }}" class="account-option">
                            <img src="{{ asset('images/icon-user.png') }}" alt="Perfil Empresa" class="account-icon">
                            <h3>Mi Perfil de Empresa</h3>
                            <p>Edita la información de tu empresa, documentos y configuraciones</p>
                        </a>
                        <a href="{{ route('empresa.dashboard') }}" class="account-option">
                            <img src="{{ asset('images/carrito-de-compras.png') }}" alt="Dashboard Empresa" class="account-icon">
                            <h3>Dashboard Empresarial</h3>
                            <p>Gestiona tus productos, ventas y estadísticas empresariales</p>
                        </a>
                    </div>

                    <div class="info-section">
                        <h3>¿Qué puedes hacer en tu perfil empresarial?</h3>
                        <ul class="info-list">
                            <li>Actualizar información de la empresa (razón social, NIT, dirección)</li>
                            <li>Gestionar documentos legales y certificaciones</li>
                            <li>Configurar métodos de pago y facturación</li>
                            <li>Administrar usuarios y permisos de la empresa</li>
                            <li>Ver reportes de ventas y estadísticas</li>
                            <li>Gestionar catálogo de productos</li>
                        </ul>
                    </div>
                @endauth

                @guest
                    <!-- Usuario no autenticado -->
                    <div class="info-section">
                        <h3>Accede a tu cuenta para gestionar tu perfil</h3>
                        <p style="margin-bottom: 2rem;">Para acceder a las opciones de gestión de cuenta, necesitas iniciar sesión.</p>
                        
                        <div class="account-options">
                            <a href="{{ route('login') }}" class="account-option">
                                <img src="{{ asset('images/icon-user.png') }}" alt="Login Usuario" class="account-icon">
                                <h3>Iniciar Sesión como Usuario</h3>
                                <p>Accede a tu cuenta personal para gestionar tus compras y perfil</p>
                            </a>
                            <a href="{{ route('empresa.login') }}" class="account-option">
                                <img src="{{ asset('images/icon-user.png') }}" alt="Login Empresa" class="account-icon">
                                <h3>Iniciar Sesión como Empresa</h3>
                                <p>Accede a tu cuenta empresarial para gestionar productos y ventas</p>
                            </a>
                        </div>
                    </div>

                    <div class="info-section">
                        <h3>¿No tienes cuenta aún?</h3>
                        <ul class="info-list">
                            <li>Regístrate como usuario para comprar productos con descuento</li>
                            <li>Regístrate como empresa para vender tus productos próximos a caducar</li>
                            <li>Disfruta de ofertas exclusivas y promociones especiales</li>
                            <li>Accede a un historial completo de tus transacciones</li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>

        <x-footer />
    </div>
</body>
</html>