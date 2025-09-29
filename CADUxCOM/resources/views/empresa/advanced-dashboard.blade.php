<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Avanzado - {{ $empresa->Nombre }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/advanced-dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            padding-top: 110px; /* Compensar el header fixed */
        }
    </style>
</head>
<body>
    <x-header-pages />
    
    <div class="dashboard-container">
        <!-- Header del Dashboard -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1 class="page-title">Dashboard Avanzado</h1>
                <p class="page-subtitle">{{ $empresa->Nombre }} - Análisis y métricas en tiempo real</p>
            </div>
            
            <div class="header-actions">
                <button class="btn btn-secondary" onclick="refreshDashboard()">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Actualizar
                </button>
            </div>
        </div>

        <!-- Métricas Principales -->
        <div class="metrics-grid">
            <div class="metric-card sales">
                <div class="metric-icon">💰</div>
                <div class="metric-content">
                    <div class="metric-value">${{ number_format($metrics['total_sales'], 0) }}</div>
                    <div class="metric-label">Ventas del Mes</div>
                    <div class="metric-change {{ $metrics['sales_growth'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $metrics['sales_growth'] >= 0 ? '+' : '' }}{{ number_format($metrics['sales_growth'], 1) }}%
                    </div>
                </div>
            </div>

            <div class="metric-card orders">
                <div class="metric-icon">📦</div>
                <div class="metric-content">
                    <div class="metric-value">{{ $metrics['monthly_orders'] }}</div>
                    <div class="metric-label">Órdenes del Mes</div>
                    <div class="metric-change positive">+{{ $metrics['unique_customers'] }} clientes</div>
                </div>
            </div>

            <div class="metric-card products">
                <div class="metric-icon">🛍️</div>
                <div class="metric-content">
                    <div class="metric-value">{{ $metrics['discounted_products'] }}</div>
                    <div class="metric-label">Productos con Descuento</div>
                    <div class="metric-change positive">{{ $metrics['active_discount_rules'] }} reglas activas</div>
                </div>
            </div>

            <div class="metric-card expiry">
                <div class="metric-icon">⏰</div>
                <div class="metric-content">
                    <div class="metric-value">{{ $metrics['expiring_products'] }}</div>
                    <div class="metric-label">Próximos a Caducar</div>
                    <div class="metric-change urgent">Requieren atención</div>
                </div>
            </div>
        </div>

        <!-- Gráficos y Análisis -->
        <div class="charts-grid">
            <!-- Gráfico de Ventas -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Ventas de los Últimos 30 Días</h3>
                    <div class="chart-actions">
                        <button class="btn btn-sm btn-outline" onclick="exportData('sales')">Exportar</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Gráfico de Tendencias -->
            <div class="chart-card">
                <div class="chart-header">
                    <h3>Tendencias Anuales</h3>
                    <div class="chart-actions">
                        <button class="btn btn-sm btn-outline" onclick="exportData('trends')">Exportar</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Análisis Detallado -->
        <div class="analysis-grid">
            <!-- Productos Más Vendidos -->
            <div class="analysis-card">
                <div class="card-header">
                    <h3>Productos Más Vendidos</h3>
                    <span class="card-badge">{{ count($topProducts) }} productos</span>
                </div>
                <div class="card-content">
                    <div class="products-list">
                        @forelse($topProducts as $item)
                            <div class="product-item">
                                <div class="product-info">
                                    <h4>{{ $item['producto']->Nombre }}</h4>
                                    <p>{{ $item['total_sold'] }} unidades vendidas</p>
                                </div>
                                <div class="product-stats">
                                    <span class="revenue">${{ number_format($item['total_revenue'], 0) }}</span>
                                    @if($item['discount_info']['has_discount'])
                                        <span class="discount-badge">{{ round($item['discount_info']['discount_percentage'], 0) }}% descuento</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="empty-state">
                                <p>No hay datos de ventas disponibles</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Análisis de Descuentos -->
            <div class="analysis-card">
                <div class="card-header">
                    <h3>Análisis de Descuentos</h3>
                    <span class="card-badge">{{ $discountAnalysis['active_rules'] }} reglas</span>
                </div>
                <div class="card-content">
                    <div class="discount-stats">
                        <div class="stat-item">
                            <span class="stat-label">Productos con descuento:</span>
                            <span class="stat-value">{{ $discountAnalysis['discounted_products'] }}/{{ $discountAnalysis['total_products'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Porcentaje promedio:</span>
                            <span class="stat-value">{{ round($discountAnalysis['average_discount'], 1) }}%</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Valor total de descuentos:</span>
                            <span class="stat-value">${{ number_format($discountAnalysis['total_discount_value'], 0) }}</span>
                        </div>
                    </div>
                    
                    <div class="discount-rules">
                        <h4>Reglas Activas</h4>
                        @forelse($discountAnalysis['rules'] as $rule)
                            <div class="rule-item">
                                <span class="rule-name">{{ $rule->name }}</span>
                                <span class="rule-value">{{ $rule->discount_value }}{{ $rule->discount_type === 'percentage' ? '%' : '$' }}</span>
                            </div>
                        @empty
                            <p class="text-gray-500">No hay reglas de descuento activas</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Próximos a Caducar -->
        <div class="expiry-section">
            <div class="section-header">
                <h2>Productos Próximos a Caducar</h2>
                <a href="{{ route('productos.index') }}" class="btn btn-primary">Gestionar Productos</a>
            </div>
            
            <div class="expiry-grid">
                @forelse($expiringProducts as $item)
                    <div class="expiry-card priority-{{ $item['expiry_status'] }}">
                        <div class="expiry-header">
                            <h4>{{ $item['producto']->Nombre }}</h4>
                            <span class="expiry-badge">{{ $item['days_until_expiry'] }} días</span>
                        </div>
                        <div class="expiry-content">
                            <p><strong>Cantidad:</strong> {{ $item['producto']->Cantidad }}</p>
                            <p><strong>Precio:</strong> ${{ number_format($item['producto']->Precio, 0) }}</p>
                            @if($item['discount_info']['has_discount'])
                                <p><strong>Descuento:</strong> {{ round($item['discount_info']['discount_percentage'], 0) }}%</p>
                                <p><strong>Precio con descuento:</strong> ${{ number_format($item['discount_info']['discounted_price'], 0) }}</p>
                            @endif
                        </div>
                        <div class="expiry-actions">
                            <a href="{{ route('productos.edit', $item['producto']) }}" class="btn btn-sm btn-primary">Editar</a>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <p>No hay productos próximos a caducar</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Estadísticas de Geolocalización -->
        @if($locationStats['has_coordinates'])
            <div class="location-section">
                <div class="section-header">
                    <h2>Estadísticas de Ubicación</h2>
                    <span class="location-status active">📍 Ubicación configurada</span>
                </div>
                
                <div class="location-stats">
                    <div class="location-metric">
                        <div class="metric-icon">👥</div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $locationStats['nearby_visits'] }}</div>
                            <div class="metric-label">Visitas por proximidad</div>
                        </div>
                    </div>
                    
                    <div class="location-metric">
                        <div class="metric-icon">🗺️</div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $locationStats['map_views'] }}</div>
                            <div class="metric-label">Visualizaciones en mapa</div>
                        </div>
                    </div>
                    
                    <div class="location-metric">
                        <div class="metric-icon">🛒</div>
                        <div class="metric-content">
                            <div class="metric-value">{{ $locationStats['location_based_orders'] }}</div>
                            <div class="metric-label">Órdenes por ubicación</div>
                        </div>
                    </div>
                    
                    <div class="location-metric">
                        <div class="metric-icon">📊</div>
                        <div class="metric-content">
                            <div class="metric-value">{{ round($locationStats['conversion_rate'], 1) }}%</div>
                            <div class="metric-label">Tasa de conversión</div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="location-section">
                <div class="section-header">
                    <h2>Configuración de Ubicación</h2>
                    <span class="location-status inactive">⚠️ Ubicación no configurada</span>
                </div>
                
                <div class="location-setup">
                    <p>{{ $locationStats['message'] }}</p>
                    <a href="{{ route('empresa.profile.edit') }}" class="btn btn-primary">Configurar Ubicación</a>
                </div>
            </div>
        @endif

        <!-- Análisis de Clientes -->
        <div class="customer-section">
            <div class="section-header">
                <h2>Análisis de Clientes</h2>
                <button class="btn btn-secondary" onclick="exportData('customers')">Exportar Datos</button>
            </div>
            
            <div class="customer-stats">
                <div class="customer-metric">
                    <div class="metric-icon">👥</div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $customerAnalysis['total_customers'] }}</div>
                        <div class="metric-label">Total de Clientes</div>
                    </div>
                </div>
                
                <div class="customer-metric">
                    <div class="metric-icon">🔄</div>
                    <div class="metric-content">
                        <div class="metric-value">{{ $customerAnalysis['repeat_customers'] }}</div>
                        <div class="metric-label">Clientes Recurrentes</div>
                    </div>
                </div>
                
                <div class="customer-metric">
                    <div class="metric-icon">📈</div>
                    <div class="metric-content">
                        <div class="metric-value">{{ round($customerAnalysis['customer_retention_rate'], 1) }}%</div>
                        <div class="metric-label">Tasa de Retención</div>
                    </div>
                </div>
                
                <div class="customer-metric">
                    <div class="metric-icon">💰</div>
                    <div class="metric-content">
                        <div class="metric-value">${{ number_format($customerAnalysis['average_order_value'], 0) }}</div>
                        <div class="metric-label">Valor Promedio de Orden</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-footer />

    <script>
        // Datos para gráficos
        const salesData = @json($salesData);
        const trendsData = @json($salesTrends);

        // Gráfico de ventas
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesData.map(item => item.formatted_date),
                datasets: [{
                    label: 'Ventas ($)',
                    data: salesData.map(item => item.sales),
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Órdenes',
                    data: salesData.map(item => item.orders),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        // Gráfico de tendencias
        const trendsCtx = document.getElementById('trendsChart').getContext('2d');
        new Chart(trendsCtx, {
            type: 'bar',
            data: {
                labels: trendsData.map(item => item.formatted_month),
                datasets: [{
                    label: 'Ventas ($)',
                    data: trendsData.map(item => item.sales),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: '#3b82f6',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Funciones de utilidad
        function refreshDashboard() {
            location.reload();
        }

        function exportData(type) {
            window.open(`/empresa/dashboard/export?type=${type}`, '_blank');
        }
    </script>
</body>
</html>
