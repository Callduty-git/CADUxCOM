@auth
<div class="notification-bell">
    <button onclick="toggleNotifications()" class="bell-button" id="notification-btn">
        <img src="{{ asset('images/notificacion.png') }}" alt="Notificaciones" class="notification-icon-img">
        <span id="notification-count" class="notification-count is-hidden">0</span>
    </button>

    <!-- Dropdown de notificaciones -->
    <div id="notification-dropdown" class="notification-dropdown is-hidden">
        
        <div class="dropdown-header">
            <h3>Notificaciones</h3>
            <button onclick="markAllAsRead()" id="mark-all-btn" class="mark-all-btn is-hidden">
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

<link rel="stylesheet" href="{{ asset('css/notification-bell.css') }}">
