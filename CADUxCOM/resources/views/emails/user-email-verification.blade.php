<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirma tu Registro - CADUxCOM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #28a745;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #28a745;
        }
        .content {
            margin-bottom: 30px;
        }
        .welcome {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            background-color: #28a745;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #218838;
        }
        .btn-container {
            text-align: center;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CADUxCOM</div>
            <h2>¡Bienvenido a CADUxCOM!</h2>
        </div>

        <div class="content">
            <div class="welcome">
                <h3>¡Hola {{ $user->name }}!</h3>
                <p>Gracias por registrarte en CADUxCOM. Estamos emocionados de tenerte como parte de nuestra comunidad.</p>
            </div>

            <p>Para completar tu registro y activar tu cuenta, por favor confirma tu dirección de correo electrónico haciendo clic en el botón de abajo:</p>

            <div class="btn-container">
                <a href="{{ $verificationUrl }}" class="btn">Confirmar mi Registro</a>
            </div>

            <p>Si el botón no funciona, puedes copiar y pegar el siguiente enlace en tu navegador:</p>
            <p style="word-break: break-all; background-color: #f8f9fa; padding: 10px; border-radius: 5px; font-family: monospace;">
                {{ $verificationUrl }}
            </p>

            <div class="warning">
                <strong>Importante:</strong> Este enlace expirará en 60 minutos por seguridad. Si no confirmas tu cuenta en este tiempo, deberás registrarte nuevamente.
            </div>

            <p>Una vez confirmada tu cuenta, podrás:</p>
            <ul>
                <li>Acceder a todos los productos disponibles</li>
                <li>Realizar compras de manera segura</li>
                <li>Agregar productos a tu lista de deseos</li>
                <li>Recibir notificaciones sobre ofertas especiales</li>
                <li>Acceder a tu historial de compras</li>
            </ul>
        </div>

        <div class="footer">
            <p>Si no te registraste en CADUxCOM, puedes ignorar este correo electrónico.</p>
            <p>Este es un mensaje automático del sistema CADUxCOM.</p>
            <p>Por favor, no responda a este correo electrónico.</p>
        </div>
    </div>
</body>
</html>

