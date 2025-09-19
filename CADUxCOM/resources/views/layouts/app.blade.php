<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/banner-carousel.css') }}">
    <link rel="stylesheet" href="{{ asset('css/subcategorias.css') }}">
    <link rel="stylesheet" href="{{ asset('css/favorites.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Custom Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="body-base">
    <div class="page-container">
        @stack('scripts')

        <!-- HEADER -->
        <x-header />
        <x-navbar />

        <!-- SEPARADOR PARA EL CARRUSEL -->
        <div class="carousel-separator" style="height: 120px; background: transparent;"></div>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="content">
            <x-banner-carousel />
            <x-subcategorias />
            {{ $slot ?? '' }} {{-- aquí se cargan las demás vistas --}}
        </main>

        <!-- FOOTER -->
        <x-footer />
    </div>
</body>
</html>
