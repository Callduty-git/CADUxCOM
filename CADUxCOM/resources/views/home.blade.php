<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
</head>
<body>
    <div class="app-wrapper">
        {{-- Componentes globales --}}
        <x-header />
        <x-navbar />
        <x-banner-carousel />
        <x-subcategorias />
        <x-all-products :productos="$productos" :categorias="$categorias" :subcategorias="$subcategorias" />

        {{-- Contenido principal flexible --}}
        <main class="content"></main>

        {{-- Footer siempre al final --}}
        <x-footer />
    </div>
</body>

