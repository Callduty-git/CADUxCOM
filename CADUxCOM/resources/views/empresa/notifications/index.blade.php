@extends('layouts.empresa')

@section('title', 'Notificaciones')

@section('content')
<div class="dashboard-container">
    <x-empresa-sidebar />
    
    <div class="dashboard-content">
        <div class="dashboard-header">
            <h1 class="welcome-title">
                <i class="fas fa-bell"></i>
                Notificaciones
            </h1>
            <p class="welcome-subtitle">Gestiona todas tus notificaciones</p>
        </div>

        <div class="notifications-container">
            <!-- Acciones rápidas -->
            <div class="notifications-actions">
                <button onclick="markAllAsRead()" class="btn btn-primary">
                    <i class="fas fa-check-double"></i>
                    <span>Marcar todas como leídas</span>
                </button>
            </div>

            @if($notifications->count() > 0)
                <div class="notifications-list">
                    @foreach($notifications as $notification)
                        <div class="notification-item {{ !$notification->read ? 'unread' : '' }}">
                            <div class="notification-content">
                                <div class="notification-main">
                                    <div class="notification-header">
                                        @php
                                            $typeIcons = [
                                                'new_order' => 'fas fa-shopping-cart',
                                                'order_status_change' => 'fas fa-sync-alt',
                                                'low_stock' => 'fas fa-exclamation-triangle'
                                            ];
                                            $typeColors = [
                                                'new_order' => '#89CF6D',
                                                'order_status_change' => '#d88ef0',
                                                'low_stock' => '#f59e0b'
                                            ];
                                        @endphp
                                        <div class="notification-icon">
                                            <i class="{{ $typeIcons[$notification->type] ?? 'fas fa-bell' }}" 
                                               style="color: {{ $typeColors[$notification->type] ?? '#6b7280' }};"></i>
                                        </div>
                                        <div class="notification-title-section">
                                            <h3 class="notification-title">{{ $notification->title }}</h3>
                                            @if(!$notification->read)
                                                <span class="notification-badge">Nuevo</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <p class="notification-message">{{ $notification->message }}</p>
                                    
                                    <div class="notification-meta">
                                        <span class="meta-item">
                                            <i class="fas fa-clock"></i> 
                                            {{ $notification->created_at->format('d/m/Y H:i') }}
                                        </span>
                                        <span class="meta-item">
                                            <i class="fas fa-calendar"></i> 
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                        @if($notification->read && $notification->read_at)
                                            <span class="meta-item read-status">
                                                <i class="fas fa-check"></i> 
                                                Leída el {{ $notification->read_at->format('d/m/Y H:i') }}
                                            </span>
                                        @endif
                                    </div>

                                    @if($notification->data)
                                        <div class="notification-details" style="border-left-color: {{ $typeColors[$notification->type] ?? '#6b7280' }};">
                                            <h4 class="details-title">Detalles adicionales:</h4>
                                            <div class="details-grid">
                                                @if(isset($notification->data['order_id']))
                                                    <div class="detail-item">
                                                        <strong>ID del Pedido:</strong> #{{ $notification->data['order_id'] }}
                                                    </div>
                                                @endif
                                                @if(isset($notification->data['customer_name']))
                                                    <div class="detail-item">
                                                        <strong>Cliente:</strong> {{ $notification->data['customer_name'] }}
                                                    </div>
                                                @endif
                                                @if(isset($notification->data['total_amount']))
                                                    <div class="detail-item">
                                                        <strong>Monto:</strong> ${{ number_format($notification->data['total_amount'], 2) }}
                                                    </div>
                                                @endif
                                                @if(isset($notification->data['product_count']))
                                                    <div class="detail-item">
                                                        <strong>Productos:</strong> {{ $notification->data['product_count'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="notification-actions">
                                    @if(!$notification->read)
                                        <button onclick="markAsRead({{ $notification->id }})" 
                                                class="btn btn-sm btn-success">
                                            <i class="fas fa-check"></i>
                                            <span>Marcar como leída</span>
                                        </button>
                                    @endif
                                    <button onclick="deleteNotification({{ $notification->id }})" 
                                            class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                        <span>Eliminar</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Paginación -->
                <div class="pagination-container" style="margin-top: 30px;">
                    {{ $notifications->links() }}
                </div>
            @else
                <div style="text-align: center; padding: 60px; background: #f8f9fa; border-radius: 12px; color: #666;">
                    <i class="fas fa-bell-slash" style="font-size: 4rem; color: #ddd; margin-bottom: 20px;"></i>
                    <h3 style="margin: 0 0 10px 0; color: #333;">No hay notificaciones</h3>
                    <p style="margin: 0; font-size: 1.1rem;">Las notificaciones aparecerán aquí cuando ocurran eventos importantes en tu empresa</p>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.notifications-container {
    max-width: 1000px;
    margin: 0 auto;
}

.notifications-actions {
    margin-bottom: 25px;
    display: flex;
    justify-content: flex-end;
}

.notification-item {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-bottom: 20px;
    padding: 20px;
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.notification-item:hover {
    box-shadow: 0 4px 16px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.notification-item.unread {
    border-left-color: #89CF6D;
    background: linear-gradient(135deg, #f8fff8 0%, #ffffff 100%);
}

.notification-item.read {
    border-left-color: #d88ef0;
    opacity: 0.8;
}

.notification-content {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 20px;
}

.notification-main {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.notification-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 8px;
}

.notification-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    color: white;
    flex-shrink: 0;
}

.notification-title-section {
    flex: 1;
}

.notification-title {
    margin: 0 0 5px 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}

.notification-message {
    margin: 0 0 10px 0;
    color: #666;
    font-size: 1rem;
    line-height: 1.5;
}

.notification-meta {
    display: flex;
    align-items: center;
    gap: 15px;
    color: #999;
    font-size: 0.9rem;
    flex-wrap: wrap;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
}

.meta-item i {
    font-size: 0.8rem;
}

.read-status {
    color: #89CF6D !important;
    font-weight: 500;
}

.notification-badge {
    background: #89CF6D;
    color: white;
    padding: 4px 8px;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.notification-details {
    margin-top: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 3px solid #6b7280;
}

.details-title {
    margin: 0 0 10px 0;
    font-size: 0.9rem;
    color: #333;
    font-weight: 600;
}

.details-grid {
    display: grid;
    gap: 8px;
}

.detail-item {
    font-size: 0.85rem;
    color: #666;
    padding: 5px 0;
}

.detail-item strong {
    color: #333;
}

.notification-actions {
    display: flex;
    flex-direction: column;
    gap: 8px;
    min-width: 150px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    justify-content: center;
}

.btn-primary, .btn-mark-all {
            background: linear-gradient(135deg, #89CF6D 0%, #7bc95f 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(137, 207, 109, 0.3);
            font-weight: 600;
        }

        .btn-primary:hover, .btn-mark-all:hover {
            background: linear-gradient(135deg, #7bc95f 0%, #6bb84f 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(137, 207, 109, 0.4);
        }

.btn-success {
    background: linear-gradient(135deg, #89CF6D 0%, #7bc95f 100%);
    color: white;
    transition: all 0.3s ease;
}

.btn-success:hover {
    background: linear-gradient(135deg, #7bc95f 0%, #6bb84f 100%);
    transform: translateY(-1px);
}

.btn-danger {
    background: linear-gradient(135deg, #d88ef0 0%, #c77ee8 100%);
    color: white;
    transition: all 0.3s ease;
}

.btn-danger:hover {
    background: linear-gradient(135deg, #c77ee8 0%, #b66ee0 100%);
    transform: translateY(-1px);
}

.btn-sm {
    padding: 6px 12px;
    font-size: 0.8rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .notification-item {
        padding: 15px;
        margin-bottom: 15px;
    }

    .notification-content {
        flex-direction: column;
        gap: 15px;
    }

    .notification-header {
        gap: 10px;
    }

    .notification-icon {
        width: 35px;
        height: 35px;
        font-size: 1rem;
    }

    .notification-title {
        font-size: 1rem;
    }

    .notification-message {
        font-size: 0.9rem;
    }

    .notification-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .notification-actions {
        flex-direction: row;
        gap: 10px;
        min-width: auto;
    }

    .notification-actions .btn {
        flex: 1;
        font-size: 0.85rem;
        padding: 8px 12px;
    }

    .notification-actions .btn span {
        display: none;
    }

    .details-grid {
        gap: 6px;
    }

    .detail-item {
        font-size: 0.8rem;
    }
}

@media (max-width: 480px) {
    .notification-item {
        padding: 12px;
        border-radius: 8px;
    }

    .notification-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .notification-badge {
        align-self: flex-start;
    }

    .notifications-actions {
        justify-content: center;
    }

    .btn-primary {
        padding: 10px 20px;
        font-size: 0.9rem;
    }

    .notification-details {
        padding: 12px;
    }
}
</style>

<script>
function markAsRead(notificationId) {
    fetch(`/empresa/notificaciones/${notificationId}/marcar-leida`, {
        method: 'POST',
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
        alert('Error al marcar la notificación como leída');
    });
}

function markAllAsRead() {
    if (confirm('¿Estás seguro de que quieres marcar todas las notificaciones como leídas?')) {
        fetch('/empresa/notificaciones/marcar-todas-leidas', {
            method: 'POST',
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
            alert('Error al marcar todas las notificaciones como leídas');
        });
    }
}

function deleteNotification(notificationId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta notificación?')) {
        fetch(`/empresa/notificaciones/${notificationId}`, {
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
            alert('Error al eliminar la notificación');
        });
    }
}
</script>
@endsection