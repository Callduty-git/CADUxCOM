<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario Registrado</title>
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
            border-bottom: 3px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #007bff;
        }
        .content {
            margin-bottom: 30px;
        }
        .user-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #007bff;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 120px;
            color: #555;
        }
        .info-value {
            flex: 1;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .alert {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CADUxCOM</div>
            <h2>Nuevo Usuario Registrado</h2>
        </div>

        <div class="content">
            <div class="alert">
                <strong>¡Atención!</strong> Se ha registrado un nuevo usuario en la plataforma CADUxCOM.
            </div>

            <h3>Información del Usuario:</h3>
            <div class="user-info">
                <div class="info-row">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $user->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Registro:</div>
                    <div class="info-value">{{ $registrationDate }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tipo de Usuario:</div>
                    <div class="info-value">Usuario Normal</div>
                </div>
            </div>

            <p>El usuario ha completado su registro y ahora puede acceder a la plataforma. Se le ha enviado un email de confirmación para verificar su cuenta.</p>
        </div>

        <div class="footer">
            <p>Este es un mensaje automático del sistema CADUxCOM.</p>
            <p>Por favor, no responda a este correo electrónico.</p>
        </div>
    </div>
</body>
</html>

