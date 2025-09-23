<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'CADUxCOM - Autenticación' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style_register.css') }}">
    @stack('styles')

    <style>
        body { background: #ffffff; font-family: 'Figtree', Arial, sans-serif; }
        .auth-container { max-width: 520px; margin: 120px auto 60px; padding: 24px; }
        @media (max-width: 768px) { .auth-container { margin-top: 100px; padding: 16px; } }
    </style>
</head>
<body>
    <!-- Header global -->
    <x-header />

    <main class="auth-container">
        {{ $slot }}
    </main>

    <!-- Footer global -->
    <x-footer />

    <!-- Scripts globales -->
    <script src="{{ asset('js/cart.js') }}"></script>
    @stack('scripts')
</body>
</html>
