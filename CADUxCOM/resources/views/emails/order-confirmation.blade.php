<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Orden - CADUxCOM</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 20px;
        }
        .order-info {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #8b5cf6;
        }
        .order-number {
            font-size: 24px;
            font-weight: 700;
            color: #8b5cf6;
            margin-bottom: 10px;
        }
        .order-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        .detail-item {
            display: flex;
            flex-direction: column;
        }
        .detail-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 5px;
        }
        .detail-value {
            font-size: 16px;
            color: #1f2937;
            font-weight: 600;
        }
        .items-section {
            margin: 30px 0;
        }
        .items-section h3 {
            color: #1f2937;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .item:last-child {
            border-bottom: none;
        }
        .item-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .item-info {
            flex: 1;
        }
        .item-name {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .item-details {
            font-size: 14px;
            color: #6b7280;
        }
        .item-price {
            font-weight: 600;
            color: #1f2937;
            text-align: right;
        }
        .totals-section {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 16px;
        }
        .total-row.final {
            font-size: 20px;
            font-weight: 700;
            color: #8b5cf6;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 15px;
        }
        .shipping-info {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .shipping-info h3 {
            color: #0369a1;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .shipping-address {
            color: #374151;
            line-height: 1.6;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 16px;
            margin: 10px;
        }
        .footer {
            background: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
            color: #6b7280;
            font-size: 14px;
        }
        .social-links {
            margin: 20px 0;
        }
        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6b7280;
            text-decoration: none;
        }
        @media (max-width: 600px) {
            .container {
                margin: 0;
                box-shadow: none;
            }
            .order-details {
                grid-template-columns: 1fr;
            }
            .item {
                flex-direction: column;
                text-align: center;
            }
            .item-price {
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>CADUxCOM</h1>
            <p>Tu orden ha sido confirmada</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                ¡Hola {{ $customerName }}!
            </div>

            <p>Gracias por tu compra. Hemos recibido tu orden y la estamos procesando. Te enviaremos una actualización cuando tu pedido sea enviado.</p>

            <!-- Order Information -->
            <div class="order-info">
                <div class="order-number">Orden #{{ $orderNumber }}</div>
                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label">Fecha de Orden</div>
                        <div class="detail-value">{{ $orderDate }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Total</div>
                        <div class="detail-value">${{ $totalAmount }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Estado</div>
                        <div class="detail-value">Pendiente de Pago</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Método de Pago</div>
                        <div class="detail-value">{{ $order->getPaymentMethodInSpanish() }}</div>
                    </div>
                </div>
            </div>

            <!-- Items -->
            <div class="items-section">
                <h3>Productos en tu Orden</h3>
                @foreach($items as $item)
                    <div class="item">
                        <img src="{{ $item->product_image_url }}" alt="{{ $item->product_name }}" class="item-image">
                        <div class="item-info">
                            <div class="item-name">{{ $item->product_name }}</div>
                            <div class="item-details">
                                Cantidad: {{ $item->quantity }} | 
                                Empresa: {{ $item->empresa_name }}
                            </div>
                        </div>
                        <div class="item-price">{{ $item->formatted_total_price }}</div>
                    </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>IVA (19%)</span>
                    <span>${{ number_format($order->tax_amount, 0, ',', '.') }}</span>
                </div>
                <div class="total-row">
                    <span>Envío</span>
                    <span>
                        @if($order->shipping_amount > 0)
                            ${{ number_format($order->shipping_amount, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </span>
                </div>
                <div class="total-row final">
                    <span>Total</span>
                    <span>${{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Shipping Information -->
            <div class="shipping-info">
                <h3>📍 Dirección de Envío</h3>
                <div class="shipping-address">
                    {{ $shippingAddress }}<br>
                    {{ $shippingCity }}, {{ $shippingState }}<br>
                    Colombia
                </div>
            </div>

            <!-- Call to Action -->
            <div class="cta-section">
                <a href="{{ $trackingUrl }}" class="cta-button">Ver Detalles de la Orden</a>
            </div>

            <p>Si tienes alguna pregunta sobre tu orden, no dudes en contactarnos. Estamos aquí para ayudarte.</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>CADUxCOM</strong></p>
            <p>Tu tienda de confianza para productos de calidad</p>
            <div class="social-links">
                <a href="#">Facebook</a>
                <a href="#">Instagram</a>
                <a href="#">Twitter</a>
            </div>
            <p>Este es un email automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>


