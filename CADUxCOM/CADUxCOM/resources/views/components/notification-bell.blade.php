@auth
<div class="notification-bell">
    <button onclick="toggleNotifications()" class="bell-button" id="notification-btn">
        <img src="{{ asset('images/notificacion.png') }}" alt="Notificaciones" class="notification-icon-img">
        <span id="notification-count" class="notification-count" style="display: none;">0</span>
    </button>

    <!-- Dropdown de notificaciones -->
    <div id="notification-dropdown" class="notification-dropdown" style="display: none;">
        
        <div class="dropdown-header">
            <h3>Notificaciones</h3>
            <button onclick="markAllAsRead()" id="mark-all-btn" class="mark-all-btn" style="display: none;">
                Marcar todas como leídas
            </button>
        </div>

        <div class="notifications-list" id="notifications-list">
            <div id="empty-notifications" class="empty-notifications">
                <p>No hay notificaciones</p>
            </div>
        </div>

        <div class="dropdown-footer">
            <a href="{{ route('notifications.index') }}" class="view-all-btn">
                Ver todas las notificaciones
            </a>
        </div>
    </div>
</div>

<script>
let notificationTimeout = null;
let isNotificationOpen = false;

function toggleNotifications() {
    const dropdown = document.getElementById('notification-dropdown');
    const btn = document.getElementById('notification-btn');
    
    if (isNotificationOpen) {
        closeNotifications();
    } else {
        openNotifications();
    }
}

function openNotifications() {
    const dropdown = document.getElementById('notification-dropdown');
    const btn = document.getElementById('notification-btn');
    
    dropdown.style.display = 'block';
    btn.classList.add('has-notifications');
    isNotificationOpen = true;
    
    loadNotifications();
    
    // Cerrar automáticamente después de 10 segundos
    notificationTimeout = setTimeout(() => {
        closeNotifications();
    }, 10000);
}

function closeNotifications() {
    const dropdown = document.getElementById('notification-dropdown');
    const btn = document.getElementById('notification-btn');
    
    dropdown.style.display = 'none';
    btn.classList.remove('has-notifications');
    isNotificationOpen = false;
    
    if (notificationTimeout) {
        clearTimeout(notificationTimeout);
        notificationTimeout = null;
    }
}

async function loadNotifications() {
    try {
        const response = await fetch('/api/notifications/unread');
        const data = await response.json();
        
        if (data.success) {
            updateNotificationCount(data.unread_count);
            updateNotificationsList(data.notifications);
        }
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

function updateNotificationCount(count) {
    const countElement = document.getElementById('notification-count');
    const btn = document.getElementById('notification-btn');
    
    if (count > 0) {
        countElement.textContent = count;
        countElement.style.display = 'block';
        btn.classList.add('has-notifications');
    } else {
        countElement.style.display = 'none';
        btn.classList.remove('has-notifications');
    }
}

function updateNotificationsList(notifications) {
    const list = document.getElementById('notifications-list');
    const empty = document.getElementById('empty-notifications');
    const markAllBtn = document.getElementById('mark-all-btn');
    
    if (notifications.length === 0) {
        empty.style.display = 'block';
        markAllBtn.style.display = 'none';
        list.innerHTML = '<div id="empty-notifications" class="empty-notifications"><p>No hay notificaciones</p></div>';
    } else {
        empty.style.display = 'none';
        markAllBtn.style.display = 'block';
        
        list.innerHTML = notifications.map(notification => `
            <div class="notification-item ${!notification.is_read ? 'unread' : ''}">
                <div class="notification-icon">
                    <span>${notification.type_icon || '🔔'}</span>
                </div>
                <div class="notification-content">
                    <h4>${notification.title}</h4>
                    <p>${notification.message}</p>
                    <span class="time-ago">${notification.time_ago}</span>
                </div>
                ${!notification.is_read ? `<button onclick="markAsRead(${notification.id})" class="mark-read-btn">✓</button>` : ''}
            </div>
        `).join('');
    }
}

async function markAsRead(notificationId) {
    try {
        const response = await fetch(`/notificaciones/${notificationId}/read`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            loadNotifications(); // Recargar notificaciones
        }
    } catch (error) {
        console.error('Error marking notification as read:', error);
    }
}

async function markAllAsRead() {
    try {
        const response = await fetch('/notificaciones/mark-all-read', {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        if (response.ok) {
            loadNotifications(); // Recargar notificaciones
        }
    } catch (error) {
        console.error('Error marking all notifications as read:', error);
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Cerrar dropdown al hacer clic fuera
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.notification-bell')) {
            closeNotifications();
        }
    });
    
    // Cerrar dropdown al hacer scroll
    window.addEventListener('scroll', () => {
        closeNotifications();
    });
    
    // Cerrar dropdown al redimensionar ventana
    window.addEventListener('resize', () => {
        closeNotifications();
    });
    
    // Cargar notificaciones iniciales
    loadNotifications();
    
    // Recargar notificaciones cada 30 segundos
    setInterval(() => {
        loadNotifications();
    }, 30000);
});
</script>
@endauth

<style>
.notification-bell {
    position: relative;
}

.bell-button {
    position: relative;
    background: none;
    border: none;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s;
    color: #6b7280;
}

.bell-button:hover {
    background: #f3f4f6;
    color: #374151;
}

.bell-button.has-notifications {
    color: #3b82f6;
}

.notification-icon-img {
    width: 32px;
    height: 32px;
    object-fit: contain;
}

.notification-count {
    position: absolute;
    top: 0;
    right: 0;
    background: #ef4444;
    color: white;
    border-radius: 50%;
    width: 1.25rem;
    height: 1.25rem;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
    transform: translate(25%, -25%);
}

.notification-dropdown {
    position: absolute;
    top: 100%;
    right: 0;
    width: 400px;
    max-height: 500px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 0.75rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 10;
    overflow: hidden;
    margin-top: 0.5rem;
}

.dropdown-header {
    padding: 1rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f9fafb;
}

.dropdown-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 600;
    color: #1f2937;
}

.mark-all-btn {
    background: none;
    border: none;
    color: #3b82f6;
    font-size: 0.75rem;
    cursor: pointer;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    transition: background 0.2s;
}

.mark-all-btn:hover {
    background: #e0e7ff;
}

.notifications-list {
    max-height: 350px;
    overflow-y: auto;
}

.notification-item {
    display: flex;
    gap: 0.75rem;
    padding: 1rem;
    border-bottom: 1px solid #f3f4f6;
    transition: background 0.2s;
    position: relative;
}

.notification-item:hover {
    background: #f9fafb;
}

.notification-item.unread {
    background: #f0f9ff;
    border-left: 3px solid #3b82f6;
}

.notification-icon {
    width: 2rem;
    height: 2rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f3f4f6;
    border-radius: 0.5rem;
    flex-shrink: 0;
    font-size: 1rem;
}

.notification-content {
    flex: 1;
    min-width: 0;
}

.notification-content h4 {
    margin: 0 0 0.25rem 0;
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
    line-height: 1.3;
}

.notification-content p {
    margin: 0 0 0.25rem 0;
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.time-ago {
    font-size: 0.625rem;
    color: #9ca3af;
}

.mark-read-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    background: #10b981;
    color: white;
    border: none;
    border-radius: 50%;
    width: 1.5rem;
    height: 1.5rem;
    font-size: 0.75rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s;
}

.mark-read-btn:hover {
    background: #059669;
}

.empty-notifications {
    padding: 2rem;
    text-align: center;
    color: #6b7280;
    font-size: 0.875rem;
}

.dropdown-footer {
    padding: 1rem;
    border-top: 1px solid #e5e7eb;
    background: #f9fafb;
}

.view-all-btn {
    display: block;
    width: 100%;
    text-align: center;
    color: #3b82f6;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem;
    border-radius: 0.375rem;
    transition: background 0.2s;
}

.view-all-btn:hover {
    background: #e0e7ff;
}

@media (max-width: 480px) {
    .notification-dropdown {
        width: 320px;
        right: -50px;
    }
}
</style>
