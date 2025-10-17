<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Usuarios | CADUxCOM Admin</title>
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

        .filters-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
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
            border-top: 4px solid #667eea;
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

        .chart-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
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

        .badge-primary {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-secondary {
            background: #f3f4f6;
            color: #374151;
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

            .export-actions {
                flex-direction: column;
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
                    <h1>👥 Reporte de Usuarios</h1>
                    <p>Análisis detallado de usuarios registrados en el sistema</p>
                </div>

                <!-- Filtros -->
                <div class="filters-card">
                    <form method="GET" action="{{ route('admin.reports.users') }}">
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
                                <label for="role">Rol</label>
                                <select id="role" name="role" class="form-control">
                                    <option value="">Todos los roles</option>
                                    <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
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
                        <div class="stat-label">Total Usuarios</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['verified']) }}</div>
                        <div class="stat-label">Email Verificado</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['admins']) }}</div>
                        <div class="stat-label">Administradores</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">{{ number_format($stats['this_month']) }}</div>
                        <div class="stat-label">Este Mes</div>
                    </div>
                </div>

                <!-- Gráfico de registros diarios -->
                <div class="chart-card">
                    <h3>📈 Registros Diarios (Últimos 30 días)</h3>
                    <div class="chart-container">
                        <canvas id="dailyChart"></canvas>
                    </div>
                </div>

                <!-- Acciones de exportación -->
                <div class="export-actions">
                    <a href="{{ route('admin.reports.export', array_merge(['type' => 'users'], request()->all())) }}" 
                       class="btn btn-secondary">
                        📊 Exportar a CSV
                    </a>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>📋 Lista de Usuarios</h3>
                    </div>
                    
                    @if($users->count() > 0)
                        <div class="table-responsive">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Email Verificado</th>
                                        <th>Fecha Registro</th>
                                        <th>Último Acceso</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge badge-primary">Administrador</span>
                                            @else
                                                <span class="badge badge-secondary">Usuario</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="badge badge-success">Verificado</span>
                                            @else
                                                <span class="badge badge-warning">Pendiente</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($user->last_login_at)
                                                {{ $user->last_login_at->format('d/m/Y H:i') }}
                                            @else
                                                <span class="badge badge-secondary">Nunca</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="pagination-wrapper">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div style="padding: 40px; text-align: center;">
                            <div class="alert alert-info">
                                📭 No se encontraron usuarios con los filtros aplicados.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>



    <script>
        // Datos para el gráfico
        const dailyData = @json($dailyRegistrations);
        
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
                    label: 'Registros de Usuarios',
                    data: dailyData.map(item => item.count),
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#667eea',
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
    </script>
</body>
</html>