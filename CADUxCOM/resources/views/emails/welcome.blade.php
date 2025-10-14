<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Bienvenido a CADUxCOM!</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #90D575 0%, #49874E 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
        }
        .header p {
            margin: 15px 0 0 0;
            opacity: 0.9;
            font-size: 18px;
        }
        .content {
            padding: 40px 30px;
        }
        .welcome-message {
            text-align: center;
            margin-bottom: 40px;
        }
        .welcome-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .welcome-title {
            font-size: 28px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .welcome-subtitle {
            font-size: 18px;
            color: #6b7280;
            margin-bottom: 30px;
        }
        .features {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin: 40px 0;
        }
        .feature {
            background: #f9fafb;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            border: 2px solid #e5e7eb;
            transition: all 0.3s ease;
        }
        .feature:hover {
            border-color: #90D575;
            transform: translateY(-2px);
        }
        .feature-icon {
            font-size: 32px;
            margin-bottom: 15px;
        }
        .feature-title {
            font-size: 16px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .feature-description {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
        }
        .cta-section {
            background: linear-gradient(135deg, #AA5FC7 0%, #8B5CF6 100%);
            color: white;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 40px 0;
        }
        .cta-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 15px;
        }
        .cta-description {
            font-size: 16px;
            opacity: 0.9;
            margin-bottom: 25px;
        }
        .cta-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }
        .cta-button {
            display: inline-block;
            background: white;
            color: #AA5FC7;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        .cta-button:hover {
            background: #f9fafb;
            transform: translateY(-1px);
        }
        .cta-button.secondary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid white;
        }
        .cta-button.secondary:hover {
            background: white;
            color: #AA5FC7;
        }
        .benefits {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 12px;
            padding: 25px;
            margin: 30px 0;
        }
        .benefits h3 {
            color: #0369a1;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        .benefit-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            font-size: 16px;
            color: #374151;
        }
        .benefit-icon {
            color: #90D575;
            font-size: 20px;
        }
        .social-section {
            text-align: center;
            margin: 40px 0;
        }
        .social-title {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .social-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .social-link {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #90D575 0%, #49874E 100%);
            color: white;
            border-radius: 50%;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            transition: all 0.3s ease;
        }
        .social-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(144, 213, 117, 0.4);
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .footer .highlight {
            color: #49874E;
            font-weight: 600;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                box-shadow: none;
            }
            .features {
                grid-template-columns: 1fr;
            }
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            .social-links {
                flex-wrap: wrap;
            }
            .header h1 {
                font-size: 28px;
            }
            .welcome-title {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>CADUxCOM</h1>
            <p>¡Tu tienda de confianza!</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Welcome Message -->
            <div class="welcome-message">
                <div class="welcome-icon">🎉</div>
                <div class="welcome-title">¡Bienvenido, {{ $userName }}!</div>
                <div class="welcome-subtitle">
                    Estamos emocionados de tenerte como parte de nuestra comunidad
                </div>
            </div>

            <p style="text-align: center; font-size: 16px; color: #6b7280; margin-bottom: 40px;">
                Gracias por registrarte en CADUxCOM. Ahora tienes acceso a una amplia gama de productos de calidad, 
                ofertas exclusivas y una experiencia de compra única.
            </p>

            <!-- Features -->
            <div class="features">
                <div class="feature">
                    <div class="feature-icon">🛒</div>
                    <div class="feature-title">Carrito Inteligente</div>
                    <div class="feature-description">
                        Guarda tus productos favoritos y compra cuando estés listo
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">❤️</div>
                    <div class="feature-title">Lista de Deseos</div>
                    <div class="feature-description">
                        Guarda productos para comprar después
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">🎫</div>
                    <div class="feature-title">Cupones de Descuento</div>
                    <div class="feature-description">
                        Aprovecha nuestras promociones especiales
                    </div>
                </div>
                <div class="feature">
                    <div class="feature-icon">⭐</div>
                    <div class="feature-title">Reseñas y Calificaciones</div>
                    <div class="feature-description">
                        Comparte tu experiencia con otros usuarios
                    </div>
                </div>
            </div>

            <!-- Benefits -->
            <div class="benefits">
                <h3>🎁 Beneficios Exclusivos para Ti</h3>
                <ul class="benefit-list">
                    <li class="benefit-item">
                        <span class="benefit-icon">✓</span>
                        <span>Puntos de fidelidad por cada compra</span>
                    </li>
                    <li class="benefit-item">
                        <span class="benefit-icon">✓</span>
                        <span>Ofertas exclusivas por email</span>
                    </li>
                    <li class="benefit-item">
                        <span class="benefit-icon">✓</span>
                        <span>Seguimiento detallado de tus órdenes</span>
                    </li>
                    <li class="benefit-item">
                        <span class="benefit-icon">✓</span>
                        <span>Soporte al cliente prioritario</span>
                    </li>
                    <li class="benefit-item">
                        <span class="benefit-icon">✓</span>
                        <span>Acceso a productos exclusivos</span>
                    </li>
                </ul>
            </div>

            <!-- Call to Action -->
            <div class="cta-section">
                <div class="cta-title">¡Comienza a Explorar!</div>
                <div class="cta-description">
                    Descubre nuestra amplia selección de productos de calidad
                </div>
                <div class="cta-buttons">
                    <a href="{{ $productsUrl }}" class="cta-button">Ver Productos</a>
                    <a href="{{ $loginUrl }}" class="cta-button secondary">Mi Cuenta</a>
                </div>
            </div>

            <!-- Social Section -->
            <div class="social-section">
                <div class="social-title">Síguenos en Redes Sociales</div>
                <div class="social-links">
                    <a href="https://www.facebook.com/share/14FJfgWzoRf/" class="social-link" target="_blank">📘</a>
                    <a href="https://www.instagram.com/caduxcom?igsh=MWxwa2t0NmJoOWtubA==" class="social-link" target="_blank">📷</a>
                    <a href="https://www.tiktok.com" class="social-link" target="_blank">🎵</a>
                </div>
            </div>

            <p style="text-align: center; font-size: 14px; color: #6b7280; margin-top: 40px;">
                Si tienes alguna pregunta, no dudes en contactarnos en 
                <span class="highlight">{{ $supportEmail }}</span>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>CADUxCOM</strong></p>
            <p>Tu tienda de confianza para productos de calidad</p>
            <p>Este es un email automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>


