<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo producto disponible</title>
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
            background: linear-gradient(135deg, #22c55e, #16a34a);
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
        .new-badge {
            background: #22c55e;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            display: inline-block;
            margin: 10px 0;
        }
        .price-info {
            background: #f0fdf4;
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
        .cta-button {
            display: inline-block;
            background: #22c55e;
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
        <h1>🆕 Nuevo Producto</h1>
        <p>Hola {{ $user->name }}, tenemos algo nuevo para ti</p>
    </div>

    <div class="content">
        <h2>Producto recién agregado</h2>
        
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
            
            <div class="new-badge">
                NUEVO PRODUCTO
            </div>
            
            <div class="price-info">
                @if($producto->PrecioOriginal > $producto->Precio)
                    <p><strong>Precio Original:</strong> <span class="original-price">${{ number_format($producto->PrecioOriginal, 2) }}</span></p>
                    <p><strong>Precio con Descuento:</strong> <span class="discount-price">${{ number_format($producto->Precio, 2) }}</span></p>
                    @php
                        $descuento = round((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100);
                    @endphp
                    <p><strong>Descuento:</strong> {{ $descuento }}%</p>
                @else
                    <p><strong>Precio:</strong> <span class="discount-price">${{ number_format($producto->Precio, 2) }}</span></p>
                @endif
            </div>
            
            <p><strong>Disponible:</strong> {{ $producto->Cantidad }} {{ $producto->Tipo }}</p>
            <p><strong>Vence:</strong> {{ $producto->Fecha_Caducidad->format('d/m/Y') }}</p>
        </div>
        
        <div style="text-align: center;">
            <a href="{{ route('productos.user.show', $producto->Id_Producto) }}" class="cta-button">
                Ver Producto
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
