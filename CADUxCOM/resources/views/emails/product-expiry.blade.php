<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto próximo a caducar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #4ade80, #22c55e);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .product-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            float: left;
            margin-right: 20px;
        }
        .product-info h3 {
            color: #1f2937;
            margin: 0 0 10px 0;
        }
        .price-info {
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
        }
        .original-price {
            text-decoration: line-through;
            color: #6b7280;
        }
        .discount-price {
            color: #dc2626;
            font-weight: bold;
            font-size: 1.2em;
        }
        .expiry-warning {
            background: #fef2f2;
            border-left: 4px solid #ef4444;
            padding: 15px;
            margin: 15px 0;
        }
        .cta-button {
            display: inline-block;
            background: #4ade80;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🚨 Alerta de Caducidad</h1>
        <p>Hola {{ $user->name }}, tenemos una alerta importante para ti</p>
    </div>

    <div class="content">
        <h2>Producto próximo a caducar</h2>
        
        <div class="product-card">
            @if($producto->Foto)
                <img src="{{ asset('storage/' . $producto->Foto) }}" alt="{{ $producto->Nombre }}" class="product-image">
            @endif
            
            <div class="product-info">
                <h3>{{ $producto->Nombre }}</h3>
                <p><strong>Marca:</strong> {{ $producto->Marca }}</p>
                <p><strong>Empresa:</strong> {{ $producto->empresa->Nombre }}</p>
                <p><strong>Descripción:</strong> {{ $producto->Descripcion }}</p>
            </div>
            
            <div style="clear: both;"></div>
            
            <div class="price-info">
                <p><strong>Precio Original:</strong> <span class="original-price">${{ number_format($producto->PrecioOriginal, 2) }}</span></p>
                <p><strong>Precio con Descuento:</strong> <span class="discount-price">${{ number_format($producto->Precio, 2) }}</span></p>
                @php
                    $descuento = round((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100);
                @endphp
                <p><strong>Descuento:</strong> {{ $descuento }}%</p>
            </div>
            
            <div class="expiry-warning">
                <h4>⚠️ Fecha de Caducidad</h4>
                <p><strong>Vence:</strong> {{ $producto->Fecha_Caducidad->format('d/m/Y') }}</p>
                <p><strong>Días restantes:</strong> {{ $daysUntilExpiry }} días</p>
                @if($daysUntilExpiry <= 3)
                    <p style="color: #dc2626; font-weight: bold;">¡URGENTE! Este producto caduca muy pronto</p>
                @elseif($daysUntilExpiry <= 7)
                    <p style="color: #ea580c; font-weight: bold;">¡Atención! Este producto caduca en pocos días</p>
                @endif
            </div>
            
            <p><strong>Disponible:</strong> {{ $producto->Cantidad }} {{ $producto->Tipo }}</p>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('productos.user.show', $producto->Id_Producto) }}" class="cta-button">
                Ver Producto y Comprar
            </a>
        </div>
        
        <div class="footer">
            <p>Este email fue enviado porque tienes productos en tu lista de deseos o has mostrado interés en productos similares.</p>
            <p>Si no deseas recibir estas notificaciones, puedes <a href="#">cancelar la suscripción</a>.</p>
            <p><strong>CADUxCOM</strong> - Reduciendo el desperdicio de alimentos</p>
        </div>
    </div>
</body>
</html>
