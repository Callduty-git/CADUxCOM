<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fuente (opcional) -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Estilos personalizados -->
    @stack('styles')
    
    <!-- Estilos específicos para guest layout -->
    <style>
        .page-container {
            padding-top: 90px; /* Compensar el header fijo */
        }
        
        /* Asegurar que el contenido no quede tapado */
        .contenedor-principal {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="page-container">
        <!-- HEADER -->
        <x-header-logreg/>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="content">
            <div class="contenedor-principal">
                <div class="formulario-contenedor">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- FOOTER -->
        <x-footer/>
    </div>
</body>
</html>
