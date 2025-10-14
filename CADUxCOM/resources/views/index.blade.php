<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificaciones - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-header-pages />
    
    <div class="notifications-container">
        <div class="notifications-header">
            <div class="header-content">
                <h1 class="page-title">Notificaciones</h1>
                <p class="page-subtitle">Mantente al día con las mejores ofertas y alertas</p>
            </div>
            
            <div class="header-actions">
                <button id="mark-all-read" class="btn btn-secondary">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Marcar todas como leídas
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters-section">
            <div class="filter-group">
                <label for="type-filter" class="filter-label">Tipo:</label>
                <select id="type-filter" class="filter-select">
                    <option value="all">Todas</option>
                    <option value="expiry_alert">Alertas de caducidad</option>
                    <option value="discount_available">Descuentos disponibles</option>
                    <option value="new_product">Nuevos productos</option>
                    <option value="location_alert">Ofertas cercanas</option>
                </select>
            </div>

            <div class="filter-group">
                <label for="priority-filter" class="filter-label">Prioridad:</label>
                <select id="priority-filter" class="filter-select">
                    <option value="all">Todas</option>
                    <option value="urgent">Urgente</option>
                    <option value="high">Alta</option>
                    <option value="medium">Media</option>
                    <option value="low">Baja</option>
                </select>
            </div>

            <div class="filter-group">
                <label class="filter-checkbox">
                    <input type="checkbox" id="unread-only" checked>
                    <span class="checkmark"></span>
                    Solo no leídas
                </label>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <div class="stat-number">{{ $notifications->total() }}</div>
                    <div class="stat-label">Total</div>
                </div>
            </div>
            <div class="stat-card unread">
                <div class="stat-icon">🔔</div>
                <div class="stat-content">
                    <div class="stat-number">{{ $notifications->where('is_read', false)->count() }}</div>
                    <div class="stat-label">No leídas</div>
                </div>
            </div>
            <div class="stat-card urgent">
                <div class="stat-icon">⚠️</div>
                <div class="stat-content">
                    <div class="stat-number">{{ $notifications->where('priority', 'urgent')->count() }}</div>
                    <div class="stat-label">Urgentes</div>
                </div>
            </div>
        </div>

        <!-- Lista de notificaciones -->
        <div class="notifications-list">
            @forelse($notifications as $notification)
                <div class="notification-item {{ $notification->is_read ? 'read' : 'unread' }} priority-{{ $notification->priority }}">
                    <div class="notification-icon">
                        <span class="type-icon">{{ $notification->type_icon }}</span>
                        @if(!$notification->is_read)
                            <div class="unread-indicator"></div>
                        @endif
                    </div>

                    <div class="notification-content">
                        <div class="notification-header">
                            <h3 class="notification-title">{{ $notification->title }}</h3>
                            <div class="notification-meta">
                                <span class="priority-badge priority-{{ $notification->priority }}">
                                    {{ ucfirst($notification->priority) }}
                                </span>
                                <span class="time-ago">{{ $notification->time_ago }}</span>
                            </div>
                        </div>

                        <p class="notification-message">{{ $notification->message }}</p>

                        @if($notification->empresa || $notification->producto)
                            <div class="notification-details">
                                @if($notification->empresa)
                                    <div class="detail-item">
                                        <span class="detail-label">Empresa:</span>
                                        <span class="detail-value">{{ $notification->empresa->Nombre }}</span>
                                    </div>
                                @endif

                                @if($notification->producto)
                                    <div class="detail-item">
                                        <span class="detail-label">Producto:</span>
                                        <span class="detail-value">{{ $notification->producto->Nombre }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="notification-actions">
                            <a href="{{ route('notifications.show', $notification) }}" class="btn btn-primary btn-sm">
                                Ver detalles
                            </a>
                            
                            @if($notification->producto)
                                <a href="{{ route('productos.show', $notification->producto) }}" class="btn btn-secondary btn-sm">
                                    Ver producto
                                </a>
                            @endif

                            @if(!$notification->is_read)
                                <button class="btn btn-outline btn-sm mark-read" data-id="{{ $notification->id }}">
                                    Marcar como leída
                                </button>
                            @endif

                            <button class="btn btn-danger btn-sm delete-notification" data-id="{{ $notification->id }}">
                                Eliminar
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">🔔</div>
                    <h3>No hay notificaciones</h3>
                    <p>No tienes notificaciones en este momento. Te notificaremos cuando haya ofertas disponibles.</p>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($notifications->hasPages())
            <div class="pagination-wrapper">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Marcar todas como leídas
            document.getElementById('mark-all-read').addEventListener('click', function() {
                if (confirm('¿Marcar todas las notificaciones como leídas?')) {
                    fetch('{{ route("notifications.mark-all-read") }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                }
            });

            // Marcar como leída individual
            document.querySelectorAll('.mark-read').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    
                    fetch(`/notificaciones/${notificationId}/read`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
                });
            });

            // Eliminar notificación
            document.querySelectorAll('.delete-notification').forEach(button => {
                button.addEventListener('click', function() {
                    const notificationId = this.getAttribute('data-id');
                    
                    if (confirm('¿Eliminar esta notificación?')) {
                        fetch(`/notificaciones/${notificationId}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                    }
                });
            });

            // Filtros
            document.getElementById('type-filter').addEventListener('change', applyFilters);
            document.getElementById('priority-filter').addEventListener('change', applyFilters);
            document.getElementById('unread-only').addEventListener('change', applyFilters);

            function applyFilters() {
                const type = document.getElementById('type-filter').value;
                const priority = document.getElementById('priority-filter').value;
                const unreadOnly = document.getElementById('unread-only').checked;

                const params = new URLSearchParams();
                if (type !== 'all') params.append('type', type);
                if (priority !== 'all') params.append('priority', priority);
                if (unreadOnly) params.append('unread_only', '1');

                window.location.href = '{{ route("notifications.index") }}?' + params.toString();
            }
        });
    </script>
</body>
</html>
