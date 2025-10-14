<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Cambio de Contraseña - CADUxCOM</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .header {
            background: linear-gradient(135deg, #49874E 0%, #89CF6D 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        
        .content {
            padding: 30px;
        }
        
        .greeting {
            font-size: 18px;
            color: #49874E;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .message {
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.8;
        }
        
        .verify-button {
            display: inline-block;
            background: linear-gradient(135deg, #49874E 0%, #89CF6D 100%);
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: all 0.3s ease;
        }
        
        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(73, 135, 78, 0.3);
        }
        
        .expiration {
            background: #d1ecf1;
            border: 1px solid #bee5eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            text-align: center;
        }
        
        .expiration p {
            margin: 0;
            color: #0c5460;
            font-weight: 600;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        
        .footer p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }
        
        .footer a {
            color: #49874E;
            text-decoration: none;
        }
        
        .footer a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 600px) {
            .container {
                margin: 0;
                border-radius: 0;
            }
            
            .header, .content, .footer {
                padding: 20px;
            }
            
            .header h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔑 CADUxCOM</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                ¡Hola {{ $user->name }}! 👋
            </div>
            
            <div class="message">
                <p>Hemos recibido una solicitud para cambiar la contraseña de tu cuenta en <strong>CADUxCOM</strong>.</p>
                <p>Para confirmar que eres tú quien está realizando esta acción, haz clic en el botón de abajo:</p>
            </div>
            
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    🔐 Cambiar Contraseña Ahora
                </a>
            </div>
            
            <div class="expiration">
                <p>⏰ Este enlace expirará en {{ $expirationTime }}</p>
            </div>
            
            <div class="message">
                <p><strong>¿No puedes hacer clic en el botón?</strong></p>
                <p>Copia y pega este enlace en tu navegador:</p>
                <p style="word-break: break-all; background: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace; font-size: 12px;">
                    {{ $verificationUrl }}
                </p>
            </div>
        </div>
        
        <div class="footer">
            <p>Este correo fue enviado desde <strong>CADUxCOM</strong></p>
            <p><strong>📧 Email oficial:</strong> caduxcom.store@gmail.com</p>
            <p>Si tienes alguna pregunta, contáctanos en nuestro sitio web</p>
        </div>
    </div>
</body>
</html>