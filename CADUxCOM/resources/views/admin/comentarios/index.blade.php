<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Reseñas - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
        }

        .admin-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .filters-section {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            margin-bottom: 2rem;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }

        .filter-group select,
        .filter-group input {
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 0.875rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
        }

        .reviews-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .table-header {
            background: #f9fafb;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: between;
            align-items: center;
        }

        .bulk-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .review-item {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 1rem;
            align-items: start;
        }

        .review-item:last-child {
            border-bottom: none;
        }

        .review-checkbox {
            margin-top: 0.25rem;
        }

        .review-content {
            flex: 1;
        }

        .review-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .review-text {
            color: #374151;
            line-height: 1.5;
            margin-bottom: 0.5rem;
        }

        .review-product {
            font-size: 0.875rem;
            color: #4f46e5;
            font-weight: 500;
        }

        .review-actions {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .pagination {
            padding: 1rem 1.5rem;
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: center;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .back-button:hover {
            opacity: 0.8;
        }

        @media (max-width: 768px) {
            .admin-container { padding: 1rem; }
            .filters-grid { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .review-item { grid-template-columns: 1fr; }
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
        <div class="admin-container">
            <div class="admin-header">
                <a href="{{ route('admin.dashboard') }}" class="back-button">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    Volver al Dashboard
                </a>
                <h1>Gestión de Reseñas</h1>
                <p>Modera y administra todas las reseñas de la plataforma</p>
            </div>

            <!-- Estadísticas -->
            <div class="stats-grid">
                <div class="stat-card">
                    <p class="stat-number">{{ $stats['total'] ?? 0 }}</p>
                    <p class="stat-label">Total Reseñas</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number">{{ $stats['today'] ?? 0 }}</p>
                    <p class="stat-label">Hoy</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number">{{ $stats['this_week'] ?? 0 }}</p>
                    <p class="stat-label">Esta Semana</p>
                </div>
                <div class="stat-card">
                    <p class="stat-number">{{ $stats['this_month'] ?? 0 }}</p>
                    <p class="stat-label">Este Mes</p>
                </div>
            </div>

            <!-- Filtros -->
            <div class="filters-section">
                <form method="GET" action="{{ route('admin.comentarios.index') }}">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label for="empresa">Empresa</label>
                            <select name="empresa" id="empresa">
                                <option value="">Todas las empresas</option>
                                @foreach($empresas as $empresa)
                                    <option value="{{ $empresa->id }}" {{ request('empresa') == $empresa->id ? 'selected' : '' }}>
                                        {{ $empresa->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-group">
                            <label for="fecha_desde">Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" value="{{ request('fecha_desde') }}">
                        </div>
                        <div class="filter-group">
                            <label for="fecha_hasta">Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </div>
                        <div class="filter-group">
                            <label for="buscar">Buscar</label>
                            <input type="text" name="buscar" id="buscar" placeholder="Buscar en contenido..." value="{{ request('buscar') }}">
                        </div>
                    </div>
                    <div style="margin-top: 1rem;">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <a href="{{ route('admin.comentarios.index') }}" class="btn btn-secondary">Limpiar</a>
                        <a href="{{ route('admin.comentarios.export') }}{{ request()->getQueryString() ? '?' . request()->getQueryString() : '' }}" class="btn btn-secondary">Exportar CSV</a>
                    </div>
                </form>
            </div>

            <!-- Tabla de reseñas -->
            <div class="reviews-table">
                <div class="table-header">
                    <h3 style="margin: 0;">Reseñas ({{ $comentarios->total() }})</h3>
                    <div class="bulk-actions">
                        <button type="button" id="select-all" class="btn btn-secondary">Seleccionar Todo</button>
                        <button type="button" id="delete-selected" class="btn btn-danger" disabled>Eliminar Seleccionados</button>
                    </div>
                </div>

                @if($comentarios->count() > 0)
                    <form id="bulk-delete-form" method="POST" action="{{ route('admin.comentarios.destroy-multiple') }}">
                        @csrf
                        @method('DELETE')
                        
                        @foreach($comentarios as $comentario)
                            <div class="review-item">
                                <input type="checkbox" name="comentarios[]" value="{{ $comentario->id }}" class="review-checkbox">
                                
                                <div class="review-content">
                                    <div class="review-meta">
                                        <span><strong>{{ $comentario->user->name ?? 'Usuario eliminado' }}</strong></span>
                                        <span>{{ $comentario->created_at->format('d/m/Y H:i') }}</span>
                                        @if($comentario->parent_id)
                                            <span class="badge">Respuesta</span>
                                        @endif
                                    </div>
                                    <div class="review-text">{{ $comentario->contenido }}</div>
                                    <div class="review-product">
                                        Producto: {{ $comentario->producto->nombre ?? 'Producto eliminado' }} 
                                        ({{ $comentario->empresa->nombre ?? 'Empresa eliminada' }})
                                    </div>
                                </div>

                                <div class="review-actions">
                                    <a href="{{ route('admin.comentarios.show', $comentario) }}" class="btn btn-primary">Ver</a>
                                    <form method="POST" action="{{ route('admin.comentarios.destroy', $comentario) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta reseña?')">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </form>

                    <div class="pagination">
                        {{ $comentarios->links() }}
                    </div>
                @else
                    <div class="empty-state">
                        <p>No se encontraron reseñas con los filtros aplicados.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>



    <script>
        // Funcionalidad para seleccionar todos los checkboxes
        document.getElementById('select-all').addEventListener('click', function() {
            const checkboxes = document.querySelectorAll('input[name="comentarios[]"]');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => cb.checked = !allChecked);
            this.textContent = allChecked ? 'Seleccionar Todo' : 'Deseleccionar Todo';
            updateDeleteButton();
        });

        // Actualizar estado del botón de eliminar
        function updateDeleteButton() {
            const checkedBoxes = document.querySelectorAll('input[name="comentarios[]"]:checked');
            const deleteBtn = document.getElementById('delete-selected');
            deleteBtn.disabled = checkedBoxes.length === 0;
        }

        // Escuchar cambios en checkboxes individuales
        document.querySelectorAll('input[name="comentarios[]"]').forEach(cb => {
            cb.addEventListener('change', updateDeleteButton);
        });

        // Eliminar seleccionados
        document.getElementById('delete-selected').addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('input[name="comentarios[]"]:checked');
            if (checkedBoxes.length > 0 && confirm(`¿Estás seguro de eliminar ${checkedBoxes.length} reseña(s)?`)) {
                document.getElementById('bulk-delete-form').submit();
            }
        });
    </script>
    </div>
</body>
</html>