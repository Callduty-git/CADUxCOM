<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Regla de Descuento - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-empresa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/discount-rules.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        /* Ajustes mínimos para vista de detalle manteniendo coherencia con discount-rules.css */
        .detail-container { max-width: 1100px; margin: 0 auto; padding: 24px; }
        .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .page-title { font-size: 24px; font-weight: 700; margin: 0; }
        .page-subtitle { color: #6b7280; margin-top: 6px; }
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .btn-primary { background: #2563eb; color: #fff; }
        .btn-secondary { background: #f3f4f6; color: #111827; border: 1px solid #e5e7eb; }
        .card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 18px; margin-bottom: 18px; }
        .card-title { font-size: 18px; font-weight: 600; margin-bottom: 12px; }
        .grid { display: grid; gap: 12px; }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .detail-item { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px dashed #e5e7eb; }
        .detail-label { color: #6b7280; }
        .detail-value { font-weight: 600; }
        .status-badge { display: inline-block; padding: 6px 10px; border-radius: 999px; font-size: 12px; font-weight: 700; }
        .status-active { background: #dcfce7; color: #166534; }
        .status-inactive { background: #fee2e2; color: #7f1d1d; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        .table th { background: #f9fafb; color: #374151; font-weight: 700; }
        .empty-state { text-align: center; color: #6b7280; padding: 18px; }
        .actions { display: flex; gap: 10px; }
    </style>
    </head>
<body>
    <x-header-empresa />
    <div class="page-container">
        <div class="discount-rules-container">
        <div class="detail-container">
            <!-- Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Regla de Descuento: {{ $discountRule->name }}</h1>
                    <p class="page-subtitle">Detalle y productos afectados por esta regla</p>
                </div>
                <div class="actions">
                    <a href="{{ route('discount-rules.discount-rules.index') }}" class="btn btn-secondary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Volver a Descuento Progresivo
                    </a>
                    <a href="{{ route('discount-rules.discount-rules.edit', $discountRule->id) }}" class="btn btn-primary">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5h2m-2 4h2m-6 4h8m-6 4h4"></path>
                        </svg>
                        Editar regla
                    </a>
                </div>
            </div>

            <!-- Resumen de la regla -->
            <div class="card">
                <div class="card-title">Resumen</div>
                <div style="margin-bottom: 10px;">
                    @if($discountRule->is_active)
                        <span class="status-badge status-active">Activa</span>
                    @else
                        <span class="status-badge status-inactive">Inactiva</span>
                    @endif
                </div>

                <div class="grid grid-cols-2">
                    <div class="detail-item">
                        <span class="detail-label">Descripción</span>
                        <span class="detail-value">{{ $discountRule->description ?: '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Días antes de caducidad</span>
                        <span class="detail-value">{{ $discountRule->days_before_expiry }} días</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Tipo de descuento</span>
                        <span class="detail-value">
                            @if($discountRule->discount_type === 'percentage')
                                {{ $discountRule->discount_value }}%
                            @else
                                ${{ number_format($discountRule->discount_value, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Descuento mínimo</span>
                        <span class="detail-value">${{ number_format($discountRule->minimum_discount ?? 0, 0, ',', '.') }}</span>
                    </div>
                    @if(!is_null($discountRule->maximum_discount))
                        <div class="detail-item">
                            <span class="detail-label">Descuento máximo</span>
                            <span class="detail-value">${{ number_format($discountRule->maximum_discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="detail-item">
                        <span class="detail-label">Precio mínimo del producto</span>
                        <span class="detail-value">${{ number_format($discountRule->minimum_product_price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Aplicación automática</span>
                        <span class="detail-value">{{ $discountRule->is_automatic ? 'Sí' : 'No' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Inicio</span>
                        <span class="detail-value">{{ $discountRule->starts_at ? $discountRule->starts_at->format('d/m/Y H:i') : '—' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Expira</span>
                        <span class="detail-value">{{ $discountRule->expires_at ? $discountRule->expires_at->format('d/m/Y H:i') : '—' }}</span>
                    </div>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="card">
                <div class="card-title">Estadísticas</div>
                <div class="grid grid-cols-2">
                    <div class="detail-item">
                        <span class="detail-label">Usos</span>
                        <span class="detail-value">{{ $discountRule->usage_count ?? ($stats['usage_count'] ?? 0) }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Ahorro total</span>
                        <span class="detail-value">${{ number_format($discountRule->total_savings ?? ($stats['total_savings'] ?? 0), 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Productos afectados -->
            <div class="card">
                <div class="card-title">Productos afectados</div>
                @if($affectedProducts && $affectedProducts->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Marca</th>
                                    <th>Precio</th>
                                    <th>Fecha de caducidad</th>
                                    <th>Aplicación del descuento</th>
                                    <th>Precio con descuento</th>
                                    <th>Días hasta caducar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($affectedProducts as $producto)
                                    <tr>
                                        <td>{{ $producto->Nombre }}</td>
                                        <td>{{ $producto->Marca }}</td>
                                        <td>${{ number_format($producto->Precio, 0, ',', '.') }}</td>
                                        <td>{{ $producto->Fecha_Caducidad ? \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') : '—' }}</td>
                                        @php
                                            $caducidad = $producto->Fecha_Caducidad ? \Carbon\Carbon::parse($producto->Fecha_Caducidad) : null;
                                            $aplicacion = $caducidad ? $caducidad->copy()->subDays($discountRule->days_before_expiry) : null;
                                            $diasHasta = $caducidad ? \Carbon\Carbon::today()->diffInDays($caducidad, false) : null;

                                            $precio = (float) ($producto->Precio ?? 0);
                                            $tipoDesc = $discountRule->discount_type ?? 'percentage';
                                            $valorDesc = (float) ($discountRule->discount_value ?? 0);

                                            $montoDesc = 0.0;
                                            if ($tipoDesc === 'percentage') {
                                                $montoDesc = $precio * ($valorDesc / 100);
                                            } else { // fixed_amount
                                                $montoDesc = $valorDesc;
                                            }

                                            if (!is_null($discountRule->minimum_discount)) {
                                                $montoDesc = max($montoDesc, (float)$discountRule->minimum_discount);
                                            }
                                            if (!is_null($discountRule->maximum_discount)) {
                                                $montoDesc = min($montoDesc, (float)$discountRule->maximum_discount);
                                            }

                                            // Solo aplica si el precio es estrictamente mayor al mínimo configurado
                                            $aplicaMinPrecio = is_null($discountRule->minimum_product_price) || $precio > (float)$discountRule->minimum_product_price;
                                            $precioFinal = $aplicaMinPrecio ? max($precio - $montoDesc, 0) : null;
                                        @endphp
                                        <td>{{ $aplicacion ? $aplicacion->format('d/m/Y') : '—' }}</td>
                                        <td>
                                            @if(is_null($precioFinal))
                                                No aplica
                                            @else
                                                ${{ number_format($precioFinal, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(is_null($diasHasta))
                                                —
                                            @elseif($diasHasta > 0)
                                                En {{ $diasHasta }} días
                                            @elseif($diasHasta === 0)
                                                Hoy
                                            @else
                                                Caducó hace {{ abs($diasHasta) }} días
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">No hay productos afectados por esta regla actualmente.</div>
                @endif
            </div>
        </div>
    </div>
    </div>
    <x-footer />
    
    <x-edit-empresa-modal />
</body>
</html>