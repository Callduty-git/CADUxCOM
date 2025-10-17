<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empresas - CADUxCOM Admin</title>
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
            text-align: center;
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
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
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .filter-input {
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .filter-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            flex-wrap: wrap;
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

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.8rem;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .table-header {
            background: #f8fafc;
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .bulk-actions {
            display: none;
            align-items: center;
            gap: 10px;
        }

        .bulk-actions.show {
            display: flex;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8fafc;
            padding: 15px 12px;
            text-align: left;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            font-size: 0.9rem;
        }

        .table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        .table tr:hover {
            background: #f9fafb;
        }

        .table-checkbox {
            width: 18px;
            height: 18px;
            accent-color: #667eea;
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .badge-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-sandbox {
            background: #e0e7ff;
            color: #3730a3;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .company-avatar {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            background: #f3f4f6;
        }

        .company-details h4 {
            margin: 0 0 4px 0;
            font-weight: 600;
            color: #374151;
        }

        .company-details p {
            margin: 0;
            color: #6b7280;
            font-size: 0.8rem;
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

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-state h3 {
            margin: 0 0 10px 0;
            color: #374151;
        }

        .quick-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 15% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h3 {
            margin: 0;
            color: #374151;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
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

            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .table {
                font-size: 0.8rem;
            }

            .table th,
            .table td {
                padding: 10px 8px;
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
                    <h1>🏢 Gestión de Empresas</h1>
                    <p>Administra todas las empresas registradas en CADUxCOM</p>
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

                <!-- Estadísticas -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['total']) }}</div>
                        <div class="stat-label">Total Empresas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['pending']) }}</div>
                        <div class="stat-label">Pendientes</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['approved']) }}</div>
                        <div class="stat-label">Aprobadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['rejected']) }}</div>
                        <div class="stat-label">Rechazadas</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">{{ number_format($stats['this_month']) }}</div>
                        <div class="stat-label">Este Mes</div>
                    </div>
                </div>

                <!-- Acciones rápidas -->
                <div class="quick-actions">
                    <a href="{{ route('admin.empresas.pending') }}" class="btn btn-warning">
                        ⏳ Ver Pendientes ({{ $stats['pending'] }})
                    </a>
                    <a href="{{ route('admin.empresas.approved') }}" class="btn btn-success">
                        ✅ Ver Aprobadas ({{ $stats['approved'] }})
                    </a>
                    <a href="{{ route('admin.empresas.rejected') }}" class="btn btn-danger">
                        ❌ Ver Rechazadas ({{ $stats['rejected'] }})
                    </a>
                </div>

                <!-- Filtros -->
                <div class="filters-card">
                    <form method="GET" action="{{ route('admin.empresas.index') }}" id="filtersForm">
                        <div class="filters-grid">
                            <div class="filter-group">
                                <label class="filter-label">🔍 Buscar</label>
                                <input type="text" 
                                       name="search" 
                                       class="filter-input" 
                                       placeholder="Nombre, NIT o email..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">📊 Estado</label>
                                <select name="status" class="filter-input">
                                    <option value="">Todos los estados</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="sandbox" {{ request('status') === 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprobada</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazada</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">🏷️ Categoría</label>
                                <select name="categoria" class="filter-input">
                                    <option value="">Todas las categorías</option>
                                    @foreach($categorias as $categoria)
                                        <option value="{{ $categoria }}" {{ request('categoria') === $categoria ? 'selected' : '' }}>
                                            {{ $categoria }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">📅 Desde</label>
                                <input type="date" 
                                       name="date_from" 
                                       class="filter-input"
                                       value="{{ request('date_from') }}">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">📅 Hasta</label>
                                <input type="date" 
                                       name="date_to" 
                                       class="filter-input"
                                       value="{{ request('date_to') }}">
                            </div>

                            <div class="filter-group">
                                <label class="filter-label">🔄 Ordenar por</label>
                                <select name="sort_by" class="filter-input">
                                    <option value="created_at" {{ request('sort_by') === 'created_at' ? 'selected' : '' }}>Fecha de registro</option>
                                    <option value="Nombre" {{ request('sort_by') === 'Nombre' ? 'selected' : '' }}>Nombre</option>
                                    <option value="status" {{ request('sort_by') === 'status' ? 'selected' : '' }}>Estado</option>
                                    <option value="categoria" {{ request('sort_by') === 'categoria' ? 'selected' : '' }}>Categoría</option>
                                </select>
                            </div>
                        </div>

                        <div class="filter-actions">
                            <button type="submit" class="btn btn-primary">
                                🔍 Filtrar
                            </button>
                            <a href="{{ route('admin.empresas.index') }}" class="btn btn-secondary">
                                🔄 Limpiar
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Tabla de empresas -->
                <div class="table-card">
                    <div class="table-header">
                        <h3>📋 Lista de Empresas ({{ $empresas->total() }})</h3>
                        
                        <!-- Acciones en lote -->
                        <div class="bulk-actions" id="bulkActions">
                            <span id="selectedCount">0 seleccionadas</span>
                            <button type="button" class="btn btn-success btn-sm" onclick="showBulkModal('approve')">
                                ✅ Aprobar
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="showBulkModal('reject')">
                                ❌ Rechazar
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" onclick="showBulkModal('delete')">
                                🗑️ Eliminar
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="clearSelection()">
                                Limpiar
                            </button>
                        </div>
                    </div>

                    @if($empresas->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="selectAll" class="table-checkbox">
                                    </th>
                                    <th>Empresa</th>
                                    <th>NIT</th>
                                    <th>Categoría</th>
                                    <th>Estado</th>
                                    <th>Fecha de Registro</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($empresas as $empresa)
                                <tr>
                                    <td>
                                        <input type="checkbox" 
                                               class="table-checkbox empresa-checkbox" 
                                               value="{{ $empresa->Id_Empresa }}">
                                    </td>
                                    <td>
                                        <div class="company-info">
                                            @if($empresa->Foto)
                                                <img src="{{ asset('storage/' . $empresa->Foto) }}" 
                                                     alt="{{ $empresa->Nombre }}" 
                                                     class="company-avatar">
                                            @else
                                                <div class="company-avatar" style="display: flex; align-items: center; justify-content: center; background: #e5e7eb; color: #6b7280;">
                                                    🏢
                                                </div>
                                            @endif
                                            <div class="company-details">
                                                <h4>{{ $empresa->Nombre }}</h4>
                                                <p>{{ $empresa->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $empresa->NIT }}</td>
                                    <td>{{ $empresa->categoria ?? 'Sin categoría' }}</td>
                                    <td>
                                        <span class="badge badge-{{ $empresa->status }}">
                                            {{ ucfirst($empresa->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $empresa->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div style="display: flex; gap: 5px; flex-wrap: wrap;">
                                            <a href="{{ route('admin.empresas.show', $empresa) }}" 
                                               class="btn btn-primary btn-sm">
                                                👁️ Ver
                                            </a>
                                            @if($empresa->status === 'pending' || $empresa->status === 'sandbox')
                                                <form method="POST" 
                                                      action="{{ route('admin.empresas.approve', $empresa) }}" 
                                                      style="display: inline;">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-success btn-sm"
                                                            onclick="return confirm('¿Aprobar esta empresa?')">
                                                        ✅
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Paginación -->
                        <div class="pagination-wrapper">
                            {{ $empresas->links() }}
                        </div>
                    @else
                        <div class="empty-state">
                            <h3>📭 No se encontraron empresas</h3>
                            <p>No hay empresas que coincidan con los filtros aplicados.</p>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- Modal para acciones en lote -->
    <div id="bulkModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="modalTitle">Confirmar Acción</h3>
                <span class="close" onclick="closeBulkModal()">&times;</span>
            </div>
            <form id="bulkForm" method="POST" action="{{ route('admin.empresas.bulk-action') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="action" id="bulkAction">
                    <div id="selectedEmpresas"></div>
                    <p id="modalMessage"></p>
                    
                    <div id="rejectionReasonDiv" style="display: none; margin-top: 15px;">
                        <label class="filter-label">Motivo del rechazo:</label>
                        <textarea name="rejection_reason" 
                                  class="filter-input" 
                                  rows="3" 
                                  placeholder="Explica el motivo del rechazo..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeBulkModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="confirmButton">
                        Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Manejo de selección múltiple
        const selectAllCheckbox = document.getElementById('selectAll');
        const empresaCheckboxes = document.querySelectorAll('.empresa-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        selectAllCheckbox.addEventListener('change', function() {
            empresaCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });

        empresaCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });

        function updateBulkActions() {
            const selected = document.querySelectorAll('.empresa-checkbox:checked');
            const count = selected.length;
            
            selectedCount.textContent = `${count} seleccionada${count !== 1 ? 's' : ''}`;
            
            if (count > 0) {
                bulkActions.classList.add('show');
            } else {
                bulkActions.classList.remove('show');
            }

            // Actualizar estado del checkbox "Seleccionar todo"
            selectAllCheckbox.indeterminate = count > 0 && count < empresaCheckboxes.length;
            selectAllCheckbox.checked = count === empresaCheckboxes.length;
        }

        function clearSelection() {
            empresaCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            selectAllCheckbox.checked = false;
            updateBulkActions();
        }

        // Modal para acciones en lote
        function showBulkModal(action) {
            const selected = document.querySelectorAll('.empresa-checkbox:checked');
            if (selected.length === 0) {
                alert('Selecciona al menos una empresa.');
                return;
            }

            const modal = document.getElementById('bulkModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const bulkAction = document.getElementById('bulkAction');
            const confirmButton = document.getElementById('confirmButton');
            const rejectionReasonDiv = document.getElementById('rejectionReasonDiv');
            const selectedEmpresas = document.getElementById('selectedEmpresas');

            // Limpiar empresas seleccionadas anteriores
            selectedEmpresas.innerHTML = '';
            
            // Agregar empresas seleccionadas al formulario
            selected.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'empresa_ids[]';
                input.value = checkbox.value;
                selectedEmpresas.appendChild(input);
            });

            bulkAction.value = action;

            switch(action) {
                case 'approve':
                    modalTitle.textContent = 'Aprobar Empresas';
                    modalMessage.textContent = `¿Estás seguro de aprobar ${selected.length} empresa${selected.length !== 1 ? 's' : ''}?`;
                    confirmButton.textContent = 'Aprobar';
                    confirmButton.className = 'btn btn-success';
                    rejectionReasonDiv.style.display = 'none';
                    break;
                case 'reject':
                    modalTitle.textContent = 'Rechazar Empresas';
                    modalMessage.textContent = `¿Estás seguro de rechazar ${selected.length} empresa${selected.length !== 1 ? 's' : ''}?`;
                    confirmButton.textContent = 'Rechazar';
                    confirmButton.className = 'btn btn-danger';
                    rejectionReasonDiv.style.display = 'block';
                    break;
                case 'delete':
                    modalTitle.textContent = 'Eliminar Empresas';
                    modalMessage.textContent = `¿Estás seguro de eliminar ${selected.length} empresa${selected.length !== 1 ? 's' : ''}? Esta acción no se puede deshacer.`;
                    confirmButton.textContent = 'Eliminar';
                    confirmButton.className = 'btn btn-danger';
                    rejectionReasonDiv.style.display = 'none';
                    break;
            }

            modal.style.display = 'block';
        }

        function closeBulkModal() {
            document.getElementById('bulkModal').style.display = 'none';
        }

        // Cerrar modal al hacer clic fuera
        window.onclick = function(event) {
            const modal = document.getElementById('bulkModal');
            if (event.target === modal) {
                closeBulkModal();
            }
        }

        // Auto-submit de filtros
        document.querySelectorAll('select[name="status"], select[name="categoria"], select[name="sort_by"]').forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filtersForm').submit();
            });
        });
    </script>
</body>
</html>