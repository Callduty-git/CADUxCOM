<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Empresas | CADUxCOM Admin</title>
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
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
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
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
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
            background: #10b981;
            color: white;
        }

        .btn-primary:hover {
            background: #059669;
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
            border-top: 4px solid #10b981;
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

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-info {
            background: #dbeafe;
            color: #1e40af;
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

        .category-list {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .category-list h3 {
            margin: 0 0 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }

        .category-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            background: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #10b981;
        }

        .category-name {
            font-weight: 600;
            color: #374151;
        }

        .category-count {
            background: #10b981;
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

            .category-grid {
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
                    <h1>🏢 Reporte de Empresas</h1>
                    <p>Análisis detallado de empresas registradas en el sistema</p>
                </div>

                <!-- Filtros -->
                <div class="filters-card">
                    <form method="GET" action="{{ route('admin.reports.companies') }}">
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
                                <label for="status">Estado</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">Todos los estados</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Aprobada</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rechazada</option>
                                    <option value="sandbox" {{ request('status') == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="categoria">Categoría</label>
                                <select id="categoria" name="categoria" class="form-control">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}" {{ request('categoria') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
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
                        <div class="stat-label">Total Empresas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['approved']) }}</div>
                        <div class="stat-label">Aprobadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['pending']) }}</div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['rejected']) }}</div>
                        <div class="stat-label">Rechazadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['this_month']) }}</div>
                        <div class="stat-label">Este Mes</div>
                    </div>
                </div>

                <!-- Gráficos -->
                <div class="charts-grid">
                    <div class="chart-card">
                        <h3>📈 Registros Diarios (Últimos 30 días)</h3>
                        <div class="chart-container">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>

                    <div class="chart-card">
                        <h3>📊 Estados de Empresas</h3>
                        <div class="chart-container">
                            <canvas id="statusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Distribución por categorías -->
                <div class="category-list">
                    <h3>📂 Distribución por Categorías</h3>
                    @if($categoriesStats->count() > 0)
                        <div class="category-grid">
                            @foreach($categoriesStats as $category)
                            <div class="category-item">
                                <div class="category-name">{{ $category->Categoria ?: 'Sin categoría' }}</div>
                                <div class="category-count">{{ $category->count }}</div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-info">
                            📭 No hay empresas registradas aún.
                        </div>
                    @endif
                </div>

                <!-- Acciones de exportación -->
                <div class="export-actions">
                    <a href="{{ route('admin.reports.export', array_merge(['type' => 'companies'], request()->all())) }}" 
                       class="btn btn-secondary">
                        📊 Exportar a CSV
                    </a>
                </div>

                <!-- Tabla de empresas -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>📋 Lista de Empresas</h3>
                    </div>
                    
                    @if($companies->count() > 0)
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>NIT</th>
                                        <th>Categoría</th>
                                        <th>Estado</th>
                                        <th>Productos</th>
                                        <th>Fecha Registro</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($companies as $company)
                                    <tr>
                                        <td>{{ $company->id }}</td>
                                        <td>{{ $company->Nombre }}</td>
                                        <td>{{ $company->Email }}</td>
                                        <td>{{ $company->NIT }}</td>
                                        <td>{{ $company->Categoria ?: 'Sin categoría' }}</td>
                                        <td>
                                            @switch($company->Estado)
                                                @case('approved')
                                                    <span class="badge badge-success">Aprobada</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge badge-warning">Pendiente</span>
                                                    @break
                                                @case('rejected')
                                                    <span class="badge badge-danger">Rechazada</span>
                                                    @break
                                                @case('sandbox')
                                                    <span class="badge badge-info">Sandbox</span>
                                                    @break
                                                @default
                                                    <span class="badge badge-warning">{{ $company->Estado }}</span>
                                            @endswitch
                                        </td>
                                        <td>{{ $company->productos_count ?? 0 }}</td>
                                        <td>{{ $company->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination-wrapper">
                            {{ $companies->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div style="padding: 40px; text-align: center;">
                            <div class="alert alert-info">
                                📭 No se encontraron empresas con los filtros aplicados.
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
        const statusData = @json($stats);
        
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
                    label: 'Registros de Empresas',
                    data: dailyData.map(item => item.count),
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
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

        // Configuración del gráfico de estados
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Aprobadas', 'Pendientes', 'Rechazadas'],
                datasets: [{
                    data: [statusData.approved, statusData.pending, statusData.rejected],
                    backgroundColor: [
                        '#10b981',
                        '#f59e0b',
                        '#ef4444'
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
                    }
                }
            }
        });
    </script>
</body>
</html>