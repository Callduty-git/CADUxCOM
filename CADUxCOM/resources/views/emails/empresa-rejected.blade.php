<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro No Aprobado - CADUxCOM</title>
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
            border-bottom: 3px solid #dc3545;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #dc3545;
        }
        .content {
            margin-bottom: 30px;
        }
        .notice {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 20px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        .reasons {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }
        .next-steps {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
            padding: 20px;
            border-radius: 5px;
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
            <h2>Registro No Aprobado</h2>
        </div>

        <div class="content">
            <div class="notice">
                <h3>Estimado/a {{ $empresa->Nombre }}</h3>
                <p>Lamentamos informarte que tu solicitud de registro como empresa vendedora en CADUxCOM no ha sido aprobada en esta ocasión.</p>
            </div>

            <div class="reasons">
                <h4>Posibles razones para la no aprobación:</h4>
                <ul>
                    <li>Documentos incompletos o ilegibles</li>
                    <li>Información inconsistente en los datos proporcionados</li>
                    <li>Certificado de cámara de comercio no válido o vencido</li>
                    <li>Datos de contacto no verificables</li>
                    <li>No cumplimiento con los requisitos de la plataforma</li>
                </ul>
            </div>

            <div class="next-steps">
                <h4>¿Qué puedes hacer?</h4>
                <p>Si deseas volver a solicitar el registro, te recomendamos:</p>
                <ul>
                    <li>Verificar que todos los documentos estén completos y legibles</li>
                    <li>Actualizar la información de contacto si es necesario</li>
                    <li>Asegurarte de que el certificado de cámara de comercio esté vigente</li>
                    <li>Revisar que todos los datos proporcionados sean correctos</li>
                </ul>
                <p>Puedes volver a registrarte en cualquier momento con la información corregida.</p>
            </div>

            <p><strong>Información de tu solicitud:</strong></p>
            <ul>
                <li><strong>Nombre de la Empresa:</strong> {{ $empresa->Nombre }}</li>
                <li><strong>Email:</strong> {{ $empresa->email }}</li>
                <li><strong>NIT:</strong> {{ $empresa->NIT }}</li>
                <li><strong>Fecha de Revisión:</strong> {{ $approvalDate }}</li>
            </ul>

            <p>Si tienes preguntas específicas sobre la decisión o necesitas aclaraciones, puedes contactarnos para obtener más información.</p>
        </div>

        <div class="footer">
            <p>Gracias por tu interés en CADUxCOM.</p>
            <p>Este es un mensaje automático del sistema CADUxCOM.</p>
            <p>Por favor, no responda a este correo electrónico.</p>
        </div>
    </div>
</body>
</html>



