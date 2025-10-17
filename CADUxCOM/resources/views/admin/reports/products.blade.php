<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Productos | CADUxCOM Admin</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .page-header {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .page-header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .page-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .filters-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #f59e0b;
            box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: #f59e0b;
            color: white;
        }

        .btn-primary:hover {
            background: #d97706;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
            border-top: 4px solid #f59e0b;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: #374151;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .charts-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .chart-card h3 {
            margin: 0 0 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .chart-container {
            position: relative;
            height: 300px;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            background: #f9fafb;
            padding: 20px 25px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .table-header h3 {
            margin: 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th,
        .data-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #f3f4f6;
        }

        .data-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #374151;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tr:hover {
            background: #f9fafb;
        }

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #e5e7eb;
        }

        .price-tag {
            background: #f59e0b;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .company-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .company-link:hover {
            text-decoration: underline;
        }

        .pagination-wrapper {
            padding: 20px 25px;
            border-top: 1px solid #e5e7eb;
            background: #f9fafb;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            border: 1px solid #93c5fd;
        }

        .export-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .price-ranges {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .price-ranges h3 {
            margin: 0 0 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .price-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .price-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #f59e0b;
        }

        .price-range {
            font-weight: 600;
            color: #374151;
        }

        .price-count {
            background: #f59e0b;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .export-actions {
                flex-direction: column;
            }

            .price-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <header class="main-header">
            <div class="left-section">
                <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
                <span class="logo-text">CADUxCOM</span>
            </div>
        </header>
        <main class="content">
            <x-admin.back-button href="{{ route('admin.reports.index') }}" />
            
            <div class="admin-container">
                <!-- Header -->
                <div class="page-header">
                    <h1>📦 Reporte de Productos</h1>
                    <p>Análisis detallado de productos registrados en el sistema</p>
                </div>

                <!-- Filtros -->
                <div class="filters-card">
                    <form method="GET" action="{{ route('admin.reports.products') }}">
                        <div class="filters-grid">
                            <div class="form-group">
                                <label for="date_from">Fecha Desde</label>
                                <input type="date" id="date_from" name="date_from" class="form-control" 
                                       value="{{ request('date_from') }}">
                            </div>
                            <div class="form-group">
                                <label for="date_to">Fecha Hasta</label>
                                <input type="date" id="date_to" name="date_to" class="form-control" 
                                       value="{{ request('date_to') }}">
                            </div>
                            <div class="form-group">
                                <label for="price_min">Precio Mínimo</label>
                                <input type="number" id="price_min" name="price_min" class="form-control" 
                                       placeholder="0" value="{{ request('price_min') }}" min="0" step="0.01">
                            </div>
                            <div class="form-group">
                                <label for="price_max">Precio Máximo</label>
                                <input type="number" id="price_max" name="price_max" class="form-control" 
                                       placeholder="Sin límite" value="{{ request('price_max') }}" min="0" step="0.01">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">
                                    🔍 Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['total']) }}</div>
                        <div class="stat-label">Total Productos</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">${{ number_format($stats['average_price'], 0) }}</div>
                        <div class="stat-label">Precio Promedio</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">${{ number_format($stats['max_price'], 0) }}</div>
                        <div class="stat-label">Precio Máximo</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">${{ number_format($stats['min_price'], 0) }}</div>
                        <div class="stat-label">Precio Mínimo</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['this_month']) }}</div>
                        <div class="stat-label">Este Mes</div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <h3>📈 Productos Registrados (Últimos 30 días)</h3>
                        <div class="chart-container">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <h3>💰 Distribución de Precios</h3>
                        <div class="chart-container">
                            <canvas id="priceChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Rangos de precios -->
                <div class="price-ranges">
                    <h3>💰 Distribución por Rangos de Precio</h3>
                    @if(count($priceRanges) > 0)
                        <div class="price-grid">
                            @foreach($priceRanges as $range => $count)
                            <div class="price-item">
                                <div class="price-range">{{ $range }}</div>
                                <div class="price-count">{{ $count }}</div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            📭 No hay productos registrados aún.
                        </div>
                    @endif
                </div>

                <!-- Acciones de exportación -->
                <div class="export-actions">
                    <a href="{{ route('admin.reports.export', array_merge(['type' => 'products'], request()->all())) }}" 
                       class="btn btn-secondary">
                        📊 Exportar a CSV
                    </a>
                </div>

                <!-- Tabla de productos -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>📋 Lista de Productos</h3>
                    </div>
                    
                    @if($products->count() > 0)
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Imagen</th>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Empresa</th>
                                        <th>Precio</th>
                                        <th>Categoría</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td>
                                            @if($product->Imagen)
                                                <img src="{{ asset('storage/' . $product->Imagen) }}" 
                                                     alt="{{ $product->Nombre }}" class="product-image">
                                            @else
                                                <div class="product-image" style="background: #f3f4f6; display: flex; align-items: center; justify-content: center; color: #9ca3af;">
                                                    📦
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->Nombre }}</td>
                                        <td>
                                            @if($product->empresa && $product->empresa->Id_Empresa)
                                                <a href="{{ route('admin.empresas.show', $product->empresa->Id_Empresa) }}" 
                                                   class="company-link">
                                                    {{ $product->empresa->Nombre }}
                                                </a>
                                            @else
                                                <span style="color: #9ca3af;">Sin empresa</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="price-tag">${{ number_format($product->Precio, 0) }}</span>
                                        </td>
                                        <td>{{ $product->Categoria ?: 'Sin categoría' }}</td>
                                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination-wrapper">
                            {{ $products->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div style="padding: 40px; text-align: center;">
                            <div class="alert alert-info">
                                📭 No se encontraron productos con los filtros aplicados.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>



    <script>
        // Datos para los gráficos
        const dailyData = @json($dailyRegistrations);
        const priceRangeData = @json($priceRanges);
        
        // Configuración del gráfico de registros diarios
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
                }),
                datasets: [{
                    label: 'Productos Registrados',
                    data: dailyData.map(item => item.count),
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245, 158, 11, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#f59e0b',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Configuración del gráfico de distribución de precios
        const priceCtx = document.getElementById('priceChart').getContext('2d');
        new Chart(priceCtx, {
            type: 'doughnut',
            data: {
                labels: priceRangeData.map(item => item.range),
                datasets: [{
                    data: priceRangeData.map(item => item.count),
                    backgroundColor: [
                        '#f59e0b',
                        '#10b981',
                        '#ef4444',
                        '#6366f1',
                        '#8b5cf6',
                        '#ec4899'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            padding: 10
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>