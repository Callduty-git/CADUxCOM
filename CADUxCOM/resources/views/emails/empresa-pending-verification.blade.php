<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro en Verificación - CADUxCOM</title>
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
            border-bottom: 3px solid #ffc107;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #ffc107;
        }
        .content {
            margin-bottom: 30px;
        }
        .welcome {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .empresa-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #ffc107;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
            color: #555;
        }
        .info-value {
            flex: 1;
        }
        .process-info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .timeline {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .timeline-icon {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: #ffc107;
            margin-right: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .timeline-icon.completed {
            background-color: #28a745;
        }
        .timeline-icon.pending {
            background-color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">CADUxCOM</div>
            <h2>Registro en Proceso de Verificación</h2>
        </div>

        <div class="content">
            <div class="welcome">
                <h3>¡Hola {{ $empresa->Nombre }}!</h3>
                <p>Gracias por registrarte en CADUxCOM como empresa vendedora.</p>
            </div>

            <div class="empresa-info">
                <h4>Información de tu Registro:</h4>
                <div class="info-row">
                    <div class="info-label">Nombre:</div>
                    <div class="info-value">{{ $empresa->Nombre }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email:</div>
                    <div class="info-value">{{ $empresa->email }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIT:</div>
                    <div class="info-value">{{ $empresa->NIT }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Fecha de Registro:</div>
                    <div class="info-value">{{ $registrationDate }}</div>
                </div>
            </div>

            <div class="process-info">
                <h4>Estado Actual: En Verificación</h4>
                <p>Tu registro ha sido recibido correctamente y está siendo revisado por nuestro equipo administrativo. Este proceso incluye:</p>
                <ul>
                    <li>Verificación de documentos (certificado de cámara de comercio)</li>
                    <li>Validación de información empresarial</li>
                    <li>Revisión de datos de contacto</li>
                </ul>
            </div>

            <div class="timeline">
                <h4>Proceso de Verificación:</h4>
                <div class="timeline-item">
                    <div class="timeline-icon completed">✓</div>
                    <span>Registro completado</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon pending">⏳</div>
                    <span>Revisión de documentos en proceso</span>
                </div>
                <div class="timeline-item">
                    <div class="timeline-icon pending">⏳</div>
                    <span>Notificación de aprobación/rechazo</span>
                </div>
            </div>

            <p><strong>¿Qué sigue?</strong></p>
            <p>Una vez completada la verificación, recibirás un email con el resultado. Si tu registro es aprobado, podrás:</p>
            <ul>
                <li>Acceder al panel de administración de empresa</li>
                <li>Subir y gestionar tus productos</li>
                <li>Recibir notificaciones de pedidos</li>
                <li>Acceder a reportes de ventas</li>
            </ul>

            <p><strong>Tiempo estimado:</strong> 1-3 días hábiles</p>
        </div>

        <div class="footer">
            <p>Si tienes alguna pregunta sobre tu registro, puedes contactarnos.</p>
            <p>Este es un mensaje automático del sistema CADUxCOM.</p>
            <p>Por favor, no responda a este correo electrónico.</p>
        </div>
    </div>
</body>
</html>



