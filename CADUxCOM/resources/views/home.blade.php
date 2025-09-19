<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CADUxCOM</title>
    
    {{-- Archivos CSS --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}"> 
    <link rel="stylesheet" href="{{ asset('css/banner-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/subcategorias.css') }}">
    <link rel="stylesheet" href="{{ asset('css/all-products.css') }}">
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    <!-- Estilos específicos para home -->
    <style>
        .app-wrapper {
            padding-top: 0; /* No padding-top porque el header es fixed */
        }
        
        /* Espaciado específico para el carrusel en la página de inicio */
        .carousel-separator {
            height: 120px !important; /* Separación mucho mayor para el carrusel */
            background: transparent;
        }
        
        /* Espaciado superior para el contenido principal */
        .content {
            margin-top: 0; /* Sin margen adicional ya que el separador del carrusel maneja el espacio */
        }
    </style>
</head>
<body>
    <div class="app-wrapper">
        {{-- Componentes globales --}}
        <x-header />
        <x-navbar />
        
        <!-- SEPARADOR PARA EL CARRUSEL -->
        <div class="carousel-separator" style="height: 50px; background: transparent;"></div>
        
        <x-banner-carousel />
        <x-subcategorias />
        <x-all-products :productos="$productos" :categorias="$categorias" :subcategorias="$subcategorias" />

        {{-- Contenido principal flexible --}}
        <main class="content"></main>

        {{-- Footer siempre al final --}}
        <x-footer />
    </div>
</body>

