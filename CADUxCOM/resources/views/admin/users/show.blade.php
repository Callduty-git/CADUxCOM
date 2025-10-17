<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Usuario - {{ $user->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .admin-header h1 {
            margin: 0 0 10px 0;
            font-size: 2.5rem;
            font-weight: 700;
        }

        .admin-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .info-card h3 {
            margin: 0 0 20px 0;
            color: #374151;
            font-size: 1.3rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
        }

        .info-value {
            color: #374151;
            font-weight: 500;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-admin {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-user {
            background: #dbeafe;
            color: #1e40af;
        }

        .badge-verified {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-unverified {
            background: #fee2e2;
            color: #991b1b;
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
            border-left: 4px solid #667eea;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .actions-bar {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
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

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .full-width-card {
            grid-column: 1 / -1;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .table th {
            background: #f8fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6b7280;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .content-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .actions-bar {
                flex-direction: column;
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
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
            <x-admin.back-button />
            
            <div class="admin-container">
                <!-- Header -->
                <div class="admin-header">
                    <h1>{{ $user->name }}</h1>
                    <p>Información detallada del usuario</p>
                </div>

                <!-- Alertas -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <!-- Estadísticas del usuario -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['total_companies'] }}</div>
                        <div class="stat-label">Empresas Registradas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['total_reviews'] }}</div>
                        <div class="stat-label">Reseñas Escritas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['avg_rating'], 1) }}</div>
                        <div class="stat-label">Calificación Promedio</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['last_login'] }}</div>
                        <div class="stat-label">Último Acceso</div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="actions-bar">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">
                        ✏️ Editar Usuario
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        ← Volver a la Lista
                    </a>
                    @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                              style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                🗑️ Eliminar Usuario
                            </button>
                        </form>
                    @endif
                </div>

                <!-- Información del usuario -->
                <div class="content-grid">
                    <div class="info-card">
                        <h3>📋 Información Personal</h3>
                        <div class="info-row">
                            <span class="info-label">Nombre:</span>
                            <span class="info-value">{{ $user->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Email:</span>
                            <span class="info-value">{{ $user->email }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Rol:</span>
                            <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                            </span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Estado del Email:</span>
                            <span class="badge {{ $user->email_verified_at ? 'badge-verified' : 'badge-unverified' }}">
                                {{ $user->email_verified_at ? 'Verificado' : 'No Verificado' }}
                            </span>
                        </div>
                    </div>

                    <div class="info-card">
                        <h3>📅 Información de Registro</h3>
                        <div class="info-row">
                            <span class="info-label">Fecha de Registro:</span>
                            <span class="info-value">{{ $user->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Última Actualización:</span>
                            <span class="info-value">{{ $user->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($user->email_verified_at)
                        <div class="info-row">
                            <span class="info-label">Email Verificado:</span>
                            <span class="info-value">{{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @endif
                        <div class="info-row">
                            <span class="info-label">ID del Usuario:</span>
                            <span class="info-value">#{{ $user->id }}</span>
                        </div>
                    </div>
                </div>

                <!-- Empresas del usuario -->
                @if($user->empresas->count() > 0)
                <div class="info-card full-width-card">
                    <h3>🏢 Empresas Registradas ({{ $user->empresas->count() }})</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Categoría</th>
                                <th>Estado</th>
                                <th>Fecha de Registro</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->empresas as $empresa)
                            <tr>
                                <td><strong>{{ $empresa->nombre }}</strong></td>
                                <td>{{ $empresa->categoria }}</td>
                                <td>
                                    <span class="badge {{ $empresa->estado === 'aprobada' ? 'badge-verified' : 'badge-unverified' }}">
                                        {{ ucfirst($empresa->estado) }}
                                    </span>
                                </td>
                                <td>{{ $empresa->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('empresa.show', $empresa->id) }}" class="btn btn-primary btn-sm" target="_blank">
                                        👁️ Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                <!-- Reseñas del usuario -->
                @if($user->comentarios->count() > 0)
                <div class="info-card full-width-card">
                    <h3>⭐ Reseñas Escritas ({{ $user->comentarios->count() }})</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Empresa</th>
                                <th>Calificación</th>
                                <th>Comentario</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->comentarios->take(10) as $comentario)
                            <tr>
                                <td><strong>{{ $comentario->empresa->nombre ?? 'N/A' }}</strong></td>
                                <td>
                                    <span style="color: #f59e0b;">
                                        {{ str_repeat('⭐', $comentario->calificacion) }}
                                    </span>
                                    ({{ $comentario->calificacion }}/5)
                                </td>
                                <td>{{ Str::limit($comentario->comentario, 100) }}</td>
                                <td>{{ $comentario->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.comentarios.show', $comentario) }}" class="btn btn-primary btn-sm">
                                        👁️ Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @if($user->comentarios->count() > 10)
                    <div style="text-align: center; margin-top: 15px;">
                        <a href="{{ route('admin.comentarios.index', ['user_id' => $user->id]) }}" class="btn btn-secondary">
                            Ver todas las reseñas ({{ $user->comentarios->count() }})
                        </a>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Estado vacío si no hay empresas ni reseñas -->
                @if($user->empresas->count() === 0 && $user->comentarios->count() === 0)
                <div class="info-card full-width-card">
                    <div class="empty-state">
                        <h3>📭 Sin Actividad</h3>
                        <p>Este usuario aún no ha registrado empresas ni ha escrito reseñas.</p>
                    </div>
                </div>
                @endif
            </div>
        </main>
    </div>

</body>
</html>