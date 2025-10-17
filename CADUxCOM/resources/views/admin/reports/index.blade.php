<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Analytics | CADUxCOM Admin</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #667eea;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        .stat-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .stat-icon {
            font-size: 2rem;
            opacity: 0.8;
        }

        .stat-title {
            font-size: 0.9rem;
            color: #6b7280;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
        }

        .stat-change {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .stat-change.positive {
            color: #10b981;
        }

        .stat-change.negative {
            color: #ef4444;
        }

        .stat-change.neutral {
            color: #6b7280;
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

        .reports-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .report-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-top: 4px solid #667eea;
        }

        .report-card h3 {
            margin: 0 0 15px 0;
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .report-card p {
            color: #6b7280;
            margin-bottom: 20px;
            line-height: 1.6;
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
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .top-companies {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .top-companies h3 {
            margin: 0 0 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .company-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .company-item:last-child {
            border-bottom: none;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .company-rank {
            background: #667eea;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .company-name {
            font-weight: 600;
            color: #374151;
        }

        .company-products {
            color: #667eea;
            font-weight: bold;
        }

        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .quick-actions h3 {
            margin: 0 0 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
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

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .charts-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .reports-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
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
            <x-admin.back-button href="{{ route('admin.dashboard') }}" />
            
            <div class="admin-container">
                <!-- Header -->
                <div class="page-header">
                    <h1>📊 Reportes y Analytics</h1>
                    <p>Dashboard completo de estadísticas y métricas del sistema</p>
                </div>

                <!-- Estadísticas principales -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Usuarios</div>
                            <div class="stat-icon">👥</div>
                        </div>
                        <div class="stat-value">{{ number_format($totalUsers) }}</div>
                        <div class="stat-change {{ $userGrowth >= 0 ? 'positive' : 'negative' }}">
                            {{ $userGrowth >= 0 ? '↗' : '↘' }} {{ number_format(abs($userGrowth), 1) }}% este mes
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Empresas</div>
                            <div class="stat-icon">🏢</div>
                        </div>
                        <div class="stat-value">{{ number_format($totalCompanies) }}</div>
                        <div class="stat-change {{ $companyGrowth >= 0 ? 'positive' : 'negative' }}">
                            {{ $companyGrowth >= 0 ? '↗' : '↘' }} {{ number_format(abs($companyGrowth), 1) }}% este mes
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Total Productos</div>
                            <div class="stat-icon">📦</div>
                        </div>
                        <div class="stat-value">{{ number_format($totalProducts) }}</div>
                        <div class="stat-change {{ $productGrowth >= 0 ? 'positive' : 'negative' }}">
                            {{ $productGrowth >= 0 ? '↗' : '↘' }} {{ number_format(abs($productGrowth), 1) }}% este mes
                        </div>
                    </div>

                    <div class="stat-card">
                        <div class="stat-header">
                            <div class="stat-title">Empresas Aprobadas</div>
                            <div class="stat-icon">✅</div>
                        </div>
                        <div class="stat-value">{{ number_format($companyStats['approved'] ?? 0) }}</div>
                        <div class="stat-change neutral">
                            {{ number_format((($companyStats['approved'] ?? 0) / max($totalCompanies, 1)) * 100, 1) }}% del total
                        </div>
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
                        <h3>🏢 Estados de Empresas</h3>
                        <div class="chart-container">
                            <canvas id="companyStatusChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Top empresas -->
                <div class="top-companies">
                    <h3>🏆 Top 10 Empresas por Productos</h3>
                    @if($topCompanies->count() > 0)
                        @foreach($topCompanies as $index => $company)
                        <div class="company-item">
                            <div class="company-info">
                                <div class="company-rank">{{ $index + 1 }}</div>
                                <div class="company-name">{{ $company->Nombre }}</div>
                            </div>
                            <div class="company-products">{{ $company->productos_count }} productos</div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-info">
                            📭 No hay empresas registradas aún.
                        </div>
                    @endif
                </div>

                <!-- Acciones rápidas -->
                <div class="quick-actions">
                    <h3>⚡ Acciones Rápidas</h3>
                    <div class="actions-grid">
                        <a href="{{ route('admin.reports.export', ['type' => 'users']) }}" class="btn btn-primary">
                            📊 Exportar Usuarios
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'companies']) }}" class="btn btn-primary">
                            🏢 Exportar Empresas
                        </a>
                        <a href="{{ route('admin.reports.export', ['type' => 'products']) }}" class="btn btn-primary">
                            📦 Exportar Productos
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            👥 Gestionar Usuarios
                        </a>
                        <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">
                            🏢 Gestionar Empresas
                        </a>
                    </div>
                </div>

                <!-- Reportes detallados -->
                <div class="reports-grid">
                    <div class="report-card">
                        <h3>👥 Reporte de Usuarios</h3>
                        <p>Análisis detallado de usuarios registrados, verificación de email, roles y patrones de registro.</p>
                        <a href="{{ route('admin.reports.users') }}" class="btn btn-success">
                            Ver Reporte Detallado
                        </a>
                    </div>

                    <div class="report-card">
                        <h3>🏢 Reporte de Empresas</h3>
                        <p>Estadísticas completas de empresas, estados de verificación, categorías y distribución geográfica.</p>
                        <a href="{{ route('admin.reports.companies') }}" class="btn btn-success">
                            Ver Reporte Detallado
                        </a>
                    </div>

                    <div class="report-card">
                        <h3>📦 Reporte de Productos</h3>
                        <p>Análisis de productos registrados, precios, categorías y tendencias de publicación.</p>
                        <a href="{{ route('admin.reports.products') }}" class="btn btn-success">
                            Ver Reporte Detallado
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>



    <script>
        // Datos para los gráficos
        const dailyData = @json($dailyRegistrations);
        const companyStatusData = @json($companyStats);
        
        // Configuración del gráfico de registros diarios
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: dailyData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('es-ES', { month: 'short', day: 'numeric' });
                }),
                datasets: [
                    {
                        label: 'Usuarios',
                        data: dailyData.map(item => item.users),
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Empresas',
                        data: dailyData.map(item => item.companies),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Productos',
                        data: dailyData.map(item => item.products),
                        borderColor: '#f59e0b',
                        backgroundColor: 'rgba(245, 158, 11, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Configuración del gráfico de estados de empresas
        const statusCtx = document.getElementById('companyStatusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(companyStatusData).map(status => {
                    const statusLabels = {
                        'pending': 'Pendientes',
                        'approved': 'Aprobadas',
                        'rejected': 'Rechazadas',
                        'sandbox': 'Sandbox'
                    };
                    return statusLabels[status] || status;
                }),
                datasets: [{
                    data: Object.values(companyStatusData),
                    backgroundColor: [
                        '#f59e0b',
                        '#10b981',
                        '#ef4444',
                        '#6366f1'
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