<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Orden - CADUxCOM</title>
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
            background: linear-gradient(135deg, #90D575 0%, #49874E 100%);
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
        .status-update {
            background: linear-gradient(135deg, #AA5FC7 0%, #8B5CF6 100%);
            color: white;
            border-radius: 12px;
            padding: 25px;
            margin: 20px 0;
            text-align: center;
        }
        .status-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .status-title {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .status-description {
            font-size: 16px;
            opacity: 0.9;
        }
        .order-info {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            border-left: 4px solid #90D575;
        }
        .order-number {
            font-size: 20px;
            font-weight: 700;
            color: #49874E;
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
        .tracking-info {
            background: #f0f9ff;
            border: 1px solid #0ea5e9;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .tracking-info h3 {
            color: #0369a1;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .tracking-number {
            font-family: monospace;
            font-size: 18px;
            font-weight: 700;
            color: #0369a1;
            background: white;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #0ea5e9;
        }
        .timeline {
            margin: 30px 0;
        }
        .timeline-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .timeline-item:last-child {
            border-bottom: none;
        }
        .timeline-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }
        .timeline-icon.completed {
            background: #90D575;
        }
        .timeline-icon.current {
            background: #AA5FC7;
        }
        .timeline-icon.pending {
            background: #d1d5db;
        }
        .timeline-content {
            flex: 1;
        }
        .timeline-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 5px;
        }
        .timeline-description {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .timeline-date {
            font-size: 0.875rem;
            color: #9ca3af;
        }
        .cta-section {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #90D575 0%, #49874E 100%);
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
        @media (max-width: 600px) {
            .container {
                margin: 0;
                box-shadow: none;
            }
            .order-details {
                grid-template-columns: 1fr;
            }
            .timeline-item {
                flex-direction: column;
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
            <p>Actualización de tu orden</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                ¡Hola {{ $customerName }}!
            </div>

            <p>Te informamos sobre una actualización importante en tu orden:</p>

            <!-- Status Update -->
            <div class="status-update">
                <div class="status-icon">
                    @if($currentStatus === 'paid')
                        💳
                    @elseif($currentStatus === 'processing')
                        ⚙️
                    @elseif($currentStatus === 'shipped')
                        🚚
                    @elseif($currentStatus === 'delivered')
                        ✅
                    @elseif($currentStatus === 'cancelled')
                        ❌
                    @else
                        📦
                    @endif
                </div>
                <div class="status-title">{{ $currentStatusInSpanish }}</div>
                <div class="status-description">
                    @if($currentStatus === 'shipped')
                        Tu orden ha sido enviada y está en camino
                    @elseif($currentStatus === 'delivered')
                        ¡Tu orden ha sido entregada exitosamente!
                    @elseif($currentStatus === 'processing')
                        Estamos preparando tu orden para el envío
                    @elseif($currentStatus === 'paid')
                        Tu pago ha sido confirmado
                    @elseif($currentStatus === 'cancelled')
                        Tu orden ha sido cancelada
                    @endif
                </div>
            </div>

            <!-- Order Information -->
            <div class="order-info">
                <div class="order-number">Orden #{{ $orderNumber }}</div>
                <div class="order-details">
                    <div class="detail-item">
                        <div class="detail-label">Estado Anterior</div>
                        <div class="detail-value">{{ $previousStatus }}</div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-label">Estado Actual</div>
                        <div class="detail-value">{{ $currentStatusInSpanish }}</div>
                    </div>
                    @if($trackingNumber)
                        <div class="detail-item">
                            <div class="detail-label">Número de Seguimiento</div>
                            <div class="detail-value">{{ $trackingNumber }}</div>
                        </div>
                    @endif
                    @if($estimatedDelivery)
                        <div class="detail-item">
                            <div class="detail-label">Tiempo Estimado</div>
                            <div class="detail-value">{{ $estimatedDelivery }}</div>
                        </div>
                    @endif
                </div>
            </div>

            @if($trackingNumber)
                <!-- Tracking Information -->
                <div class="tracking-info">
                    <h3>📦 Información de Seguimiento</h3>
                    <p>Puedes rastrear tu paquete usando el siguiente número:</p>
                    <div class="tracking-number">{{ $trackingNumber }}</div>
                    <p style="margin-top: 10px; font-size: 14px; color: #6b7280;">
                        Visita el sitio web de la transportadora para más detalles.
                    </p>
                </div>
            @endif

            <!-- Timeline -->
            <div class="timeline">
                <h3 style="color: #1f2937; margin-bottom: 20px;">📋 Progreso de tu Orden</h3>
                
                <div class="timeline-item">
                    <div class="timeline-icon completed">✓</div>
                    <div class="timeline-content">
                        <div class="timeline-title">Orden Creada</div>
                        <div class="timeline-description">Tu orden ha sido recibida y está siendo procesada</div>
                    </div>
                </div>

                @if(in_array($currentStatus, ['paid', 'processing', 'shipped', 'delivered']))
                    <div class="timeline-item">
                        <div class="timeline-icon completed">✓</div>
                        <div class="timeline-content">
                            <div class="timeline-title">Pago Confirmado</div>
                            <div class="timeline-description">Tu pago ha sido procesado exitosamente</div>
                        </div>
                    </div>
                @endif

                @if(in_array($currentStatus, ['processing', 'shipped', 'delivered']))
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $currentStatus === 'processing' ? 'current' : 'completed' }}">
                            {{ $currentStatus === 'processing' ? '⚙️' : '✓' }}
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">En Procesamiento</div>
                            <div class="timeline-description">Preparando tu orden para el envío</div>
                        </div>
                    </div>
                @endif

                @if(in_array($currentStatus, ['shipped', 'delivered']))
                    <div class="timeline-item">
                        <div class="timeline-icon {{ $currentStatus === 'shipped' ? 'current' : 'completed' }}">
                            {{ $currentStatus === 'shipped' ? '🚚' : '✓' }}
                        </div>
                        <div class="timeline-content">
                            <div class="timeline-title">Enviada</div>
                            <div class="timeline-description">Tu orden está en camino</div>
                            @if($shippedAt)
                                <div class="timeline-date">Enviada el {{ $shippedAt }}</div>
                            @endif
                        </div>
                    </div>
                @endif

                @if($currentStatus === 'delivered')
                    <div class="timeline-item">
                        <div class="timeline-icon completed">✓</div>
                        <div class="timeline-content">
                            <div class="timeline-title">Entregada</div>
                            <div class="timeline-description">¡Tu orden ha sido entregada exitosamente!</div>
                            @if($deliveredAt)
                                <div class="timeline-date">Entregada el {{ $deliveredAt }}</div>
                            @endif
                        </div>
                    </div>
                @endif
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
            <p>Este es un email automático, por favor no respondas a este mensaje.</p>
        </div>
    </div>
</body>
</html>


