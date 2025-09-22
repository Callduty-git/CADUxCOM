<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Registro Aprobado! - CADUxCOM</title>
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
        .success {
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
        .features {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #28a745;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CADUxCOM</div>
            <h2>¡Felicidades!</h2>
        </div>

        <div class="content">
            <div class="success">
                <h3>¡Tu registro ha sido APROBADO!</h3>
                <p>Estimado/a <strong>{{ $empresa->Nombre }}</strong>, nos complace informarte que tu solicitud de registro como empresa vendedora en CADUxCOM ha sido aprobada.</p>
            </div>

            <p>Ahora puedes acceder a tu panel de administración y comenzar a gestionar tu negocio en nuestra plataforma.</p>

            <div class="btn-container">
                <a href="{{ url('/login') }}" class="btn">Acceder a mi Panel</a>
            </div>

            <div class="features">
                <h4>¿Qué puedes hacer ahora?</h4>
                <ul>
                    <li><strong>Gestionar Productos:</strong> Subir, editar y eliminar tus productos</li>
                    <li><strong>Control de Inventario:</strong> Llevar un registro detallado de tu stock</li>
                    <li><strong>Gestión de Pedidos:</strong> Recibir y procesar órdenes de clientes</li>
                    <li><strong>Reportes de Ventas:</strong> Acceder a estadísticas detalladas</li>
                    <li><strong>Configuración de Perfil:</strong> Actualizar información de tu empresa</li>
                    <li><strong>Notificaciones:</strong> Recibir alertas sobre nuevas órdenes y actualizaciones</li>
                </ul>
            </div>

            <p><strong>Información de tu cuenta:</strong></p>
            <ul>
                <li><strong>Email:</strong> {{ $empresa->email }}</li>
                <li><strong>NIT:</strong> {{ $empresa->NIT }}</li>
                <li><strong>Fecha de Aprobación:</strong> {{ $approvalDate }}</li>
            </ul>

            <p>Si tienes alguna pregunta o necesitas ayuda para comenzar, no dudes en contactarnos. ¡Estamos aquí para ayudarte a hacer crecer tu negocio!</p>
        </div>

        <div class="footer">
            <p>¡Bienvenido a la familia CADUxCOM!</p>
            <p>Este es un mensaje automático del sistema CADUxCOM.</p>
            <p>Por favor, no responda a este correo electrónico.</p>
        </div>
    </div>
</body>
</html>



