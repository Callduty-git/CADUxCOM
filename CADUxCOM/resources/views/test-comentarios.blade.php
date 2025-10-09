<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prueba Comentarios</title>
    <link rel="stylesheet" href="{{ asset('css/comentarios.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Prueba del Sistema de Comentarios</h1>
    
    {{-- Simular un producto para la prueba --}}
    @php
        $producto = new stdClass();
        $producto->Id_Producto = 4;
        $producto->Nombre = 'Arroz Blanco 500g';
    @endphp
    
    <x-comentarios :producto="$producto" />
</body>
</html>
