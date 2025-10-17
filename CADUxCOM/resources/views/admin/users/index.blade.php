<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Panel de Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container {
            max-width: 1400px;
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

        .filters-section {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 600;
            margin-bottom: 5px;
            color: #374151;
        }

        .filter-group input,
        .filter-group select {
            padding: 10px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        .actions-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
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

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
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

        .table-container {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8fafc;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
        }

        .table tr:hover {
            background: #f9fafb;
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

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .pagination-wrapper {
            padding: 20px;
            display: flex;
            justify-content: center;
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

        .checkbox-column {
            width: 40px;
        }

        .bulk-actions {
            display: none;
            background: #fef3c7;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #f59e0b;
        }

        .bulk-actions.show {
            display: block;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .actions-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .table-container {
                overflow-x: auto;
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
                    <h1>Gestión de Usuarios</h1>
                    <p>Administra todos los usuarios registrados en CADUxCOM</p>
                </div>

                <!-- Estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['total_users'] }}</div>
                        <div class="stat-label">Total de Usuarios</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['verified_users'] }}</div>
                        <div class="stat-label">Usuarios Verificados</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['admin_users'] }}</div>
                        <div class="stat-label">Administradores</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['users_with_companies'] }}</div>
                        <div class="stat-label">Con Empresas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ $stats['users_with_reviews'] }}</div>
                        <div class="stat-label">Con Reseñas</div>
                    </div>
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

                <!-- Filtros -->
                <div class="filters-section">
                    <form method="GET" id="filtersForm">
                        <div class="filters-grid">
                            <div class="filter-group">
                                <label for="search">Buscar Usuario</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                       placeholder="Nombre o email...">
                            </div>
                            <div class="filter-group">
                                <label for="role">Rol</label>
                                <select id="role" name="role">
                                    <option value="">Todos los roles</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                                    <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>Usuario</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="email_verified">Estado de Verificación</label>
                                <select id="email_verified" name="email_verified">
                                    <option value="">Todos</option>
                                    <option value="verified" {{ request('email_verified') === 'verified' ? 'selected' : '' }}>Verificados</option>
                                    <option value="unverified" {{ request('email_verified') === 'unverified' ? 'selected' : '' }}>No Verificados</option>
                                </select>
                            </div>
                            <div class="filter-group">
                                <label for="date_from">Desde</label>
                                <input type="date" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="filter-group">
                                <label for="date_to">Hasta</label>
                                <input type="date" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="actions-bar">
                            <div>
                                <button type="submit" class="btn btn-primary">🔍 Filtrar</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">🔄 Limpiar</a>
                            </div>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-success">➕ Nuevo Usuario</a>
                        </div>
                    </form>
                </div>

                <!-- Acciones en lote -->
                <div class="bulk-actions" id="bulkActions">
                    <strong>Acciones en lote:</strong>
                    <button type="button" class="btn btn-danger btn-sm" onclick="bulkDelete()">🗑️ Eliminar Seleccionados</button>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="clearSelection()">❌ Cancelar</button>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-container">
                    <form id="bulkForm" method="POST" action="{{ route('admin.users.destroy.multiple') }}">
                        @csrf
                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="checkbox-column">
                                        <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                    </th>
                                    <th>Usuario</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Verificación</th>
                                    <th>Empresas</th>
                                    <th>Reseñas</th>
                                    <th>Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" 
                                                   class="user-checkbox" onchange="updateBulkActions()">
                                        </td>
                                        <td>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                @if($user->id === auth()->id())
                                                    <span class="badge badge-admin">TÚ</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge {{ $user->role === 'admin' ? 'badge-admin' : 'badge-user' }}">
                                                {{ $user->role === 'admin' ? 'Administrador' : 'Usuario' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $user->email_verified_at ? 'badge-verified' : 'badge-unverified' }}">
                                                {{ $user->email_verified_at ? 'Verificado' : 'No Verificado' }}
                                            </span>
                                        </td>
                                        <td>{{ $user->empresas_count }}</td>
                                        <td>{{ $user->comentarios_count }}</td>
                                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-primary btn-sm">👁️</a>
                                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-secondary btn-sm">✏️</a>
                                                @if($user->id !== auth()->id())
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                          style="display: inline;" onsubmit="return confirm('¿Estás seguro?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" style="text-align: center; padding: 40px; color: #6b7280;">
                                            No se encontraron usuarios con los filtros aplicados.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </form>

                    @if($users->hasPages())
                        <div class="pagination-wrapper">
                            {{ $users->appends(request()->query())->links() }}
                        </div>
                    @endif
                </div>
    </div>
        </main>
    </div>

    <script>
        function toggleSelectAll() {
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.user-checkbox');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
            
            updateBulkActions();
        }

        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            
            if (checkboxes.length > 0) {
                bulkActions.classList.add('show');
            } else {
                bulkActions.classList.remove('show');
            }
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.user-checkbox');
            const selectAll = document.getElementById('selectAll');
            
            checkboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAll.checked = false;
            
            updateBulkActions();
        }

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.user-checkbox:checked');
            
            if (checkboxes.length === 0) {
                alert('Selecciona al menos un usuario para eliminar.');
                return;
            }
            
            if (confirm(`¿Estás seguro de que quieres eliminar ${checkboxes.length} usuario(s)?`)) {
                document.getElementById('bulkForm').submit();
            }
        }

        // Auto-submit form on filter change
        document.querySelectorAll('#filtersForm select').forEach(select => {
            select.addEventListener('change', () => {
                document.getElementById('filtersForm').submit();
            });
        });
    </script>
</body>
</html>