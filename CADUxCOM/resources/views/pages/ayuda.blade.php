<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Ayuda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    <!-- Estilos específicos para la página de ayuda -->
    <style>
        /* Espaciado superior específico para la página de ayuda */
        .page-container {
            padding-top: 90px; /* Espacio mínimo para el header fijo */
        }
        
        .content {
            margin-top: 10px; /* Espacio adicional reducido */
        }
        
        /* Responsive para el espaciado */
        @media (max-width: 768px) {
            .page-container {
                padding-top: 100px; /* Espacio reducido en móviles */
            }
            
            .content {
                margin-top: 5px;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />
        <main class="content" style="padding: 24px;">
            <h1>Centro de Ayuda</h1>
            <p>Encuentra respuestas a las preguntas frecuentes.</p>
        </main>
        <x-footer />
    </div>
</body>
</html>

