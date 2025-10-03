/**
 * Sistema de Notificaciones Elegante para CADUxCOM
 * Maneja notificaciones toast, mensajes de sesión y errores de formularios
 */

class NotificationSystem {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Crear contenedor de notificaciones si no existe
        this.createContainer();
        
        // Mostrar mensajes de sesión si existen
        this.showSessionMessages();
        
        // Auto-ocultar notificaciones después de 5 segundos
        this.setupAutoHide();
    }

    createContainer() {
        if (!document.getElementById('notification-container')) {
            this.container = document.createElement('div');
            this.container.id = 'notification-container';
            this.container.className = 'notification-container';
            this.container.setAttribute('role', 'region');
            this.container.setAttribute('aria-live', 'polite');
            this.container.setAttribute('aria-atomic', 'true');
            document.body.appendChild(this.container);
        } else {
            this.container = document.getElementById('notification-container');
        }
    }

    showSessionMessages() {
        // Buscar mensajes de sesión en la página
        const sessionMessages = document.querySelectorAll('.session-message');
        sessionMessages.forEach(message => {
            this.showSessionMessage(message);
        });
    }

    showSessionMessage(element) {
        element.classList.add('show');
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            this.hideSessionMessage(element);
        }, 5000);
    }

    hideSessionMessage(element) {
        element.classList.remove('show');
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
        }, 400);
    }

    /**
     * Muestra una notificación toast
     * @param {string} message - Mensaje a mostrar
     * @param {string} type - Tipo: success, error, warning, info
     * @param {string} title - Título opcional
     * @param {number} duration - Duración en milisegundos (0 = no auto-hide)
     */
    show(message, type = 'info', title = '', duration = 5000) {
        const notification = this.createNotification(message, type, title);
        this.container.appendChild(notification);

        // Trigger reflow para la animación
        notification.offsetHeight;
        notification.classList.add('show');

        // Auto-ocultar si se especifica duración
        if (duration > 0) {
            this.setupProgressBar(notification, duration);
            setTimeout(() => {
                this.hide(notification);
            }, duration);
        }

        return notification;
    }

    createNotification(message, type, title) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', type === 'error' ? 'assertive' : 'polite');

        const icon = this.getIcon(type);
        const titleText = title || this.getDefaultTitle(type);

        notification.innerHTML = `
            <div class="notification-icon">${icon}</div>
            <div class="notification-content">
                ${title ? `<div class="notification-title">${titleText}</div>` : ''}
                <div class="notification-message">${message}</div>
            </div>
            <button class="notification-close" aria-label="Cerrar notificación" onclick="notificationSystem.hide(this.parentElement)">×</button>
            <div class="notification-progress"></div>
        `;

        return notification;
    }

    getIcon(type) {
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        return icons[type] || icons.info;
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Éxito',
            error: 'Error',
            warning: 'Advertencia',
            info: 'Información'
        };
        return titles[type] || titles.info;
    }

    setupProgressBar(notification, duration) {
        const progressBar = notification.querySelector('.notification-progress');
        if (progressBar) {
            progressBar.style.width = '100%';
            progressBar.style.transition = `width ${duration}ms linear`;
            
            // Trigger reflow
            progressBar.offsetWidth;
            progressBar.style.width = '0%';
        }
    }

    hide(notification) {
        notification.classList.add('hide');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 400);
    }

    setupAutoHide() {
        // Auto-ocultar notificaciones existentes
        setInterval(() => {
            const notifications = this.container.querySelectorAll('.notification:not(.hide)');
            notifications.forEach(notification => {
                const created = notification.dataset.created;
                if (created && Date.now() - parseInt(created) > 5000) {
                    this.hide(notification);
                }
            });
        }, 1000);
    }

    // Métodos de conveniencia
    success(message, title = 'Éxito', duration = 5000) {
        return this.show(message, 'success', title, duration);
    }

    error(message, title = 'Error', duration = 7000) {
        return this.show(message, 'error', title, duration);
    }

    warning(message, title = 'Advertencia', duration = 6000) {
        return this.show(message, 'warning', title, duration);
    }

    info(message, title = 'Información', duration = 5000) {
        return this.show(message, 'info', title, duration);
    }
}

// Crear instancia global cuando el DOM esté listo
let notificationSystem;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        notificationSystem = new NotificationSystem();
    });
} else {
    notificationSystem = new NotificationSystem();
}

// Función global para compatibilidad con código existente
function showNotification(message, type = 'info', title = '', duration = 5000) {
    return notificationSystem.show(message, type, title, duration);
}

// Función para reemplazar alerts básicos
function showAlert(message, type = 'info') {
    const alertTypes = {
        'success': 'success',
        'error': 'error',
        'warning': 'warning',
        'info': 'info'
    };
    
    const notificationType = alertTypes[type] || 'info';
    return notificationSystem.show(message, notificationType);
}

// Interceptar alerts básicos de JavaScript
const originalAlert = window.alert;
window.alert = function(message) {
    // Detectar tipo de mensaje basado en el contenido
    let type = 'info';
    if (message.includes('✅') || message.includes('exitosamente') || message.includes('correctamente')) {
        type = 'success';
    } else if (message.includes('❌') || message.includes('Error') || message.includes('problema')) {
        type = 'error';
    } else if (message.includes('⚠') || message.includes('Advertencia')) {
        type = 'warning';
    }
    
    showAlert(message, type);
};

// Interceptar confirm básico de JavaScript cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    const originalConfirm = window.confirm;
    window.confirm = function(message) {
        // Detectar tipo de mensaje basado en el contenido
        let type = 'danger';
        let title = 'Eliminar Producto';
        
        if (message.includes('eliminar') || message.includes('borrar') || message.includes('delete')) {
            type = 'danger';
            title = 'Eliminar Producto';
        } else if (message.includes('vaciar') || message.includes('clear')) {
            type = 'danger';
            title = 'Vaciar';
        }
        
        // Mostrar modal elegante
        console.log('Interceptando confirm:', message, 'showConfirmation disponible:', typeof showConfirmation);
        if (typeof showConfirmation === 'function') {
            return showConfirmation(message, type, title);
        } else {
            console.log('Usando confirm original');
            // Fallback al confirm original si no está disponible
            return originalConfirm(message);
        }
    };
});

// Función para mostrar mensajes de sesión de Laravel
function showSessionMessage(message, type = 'info') {
    const messageElement = document.createElement('div');
    messageElement.className = `session-message ${type}`;
    messageElement.innerHTML = `
        <div class="notification-icon">${notificationSystem.getIcon(type)}</div>
        <div class="notification-content">
            <div class="notification-message">${message}</div>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">×</button>
    `;
    
    document.body.appendChild(messageElement);
    notificationSystem.showSessionMessage(messageElement);
}

// Función para mostrar modal de confirmación elegante
function showConfirmation(message, type = 'warning', title = 'Confirmar Acción') {
    return new Promise((resolve) => {
        // Crear modal
        const modal = document.createElement('div');
        modal.className = 'confirmation-modal';
        
        const iconClass = type === 'danger' ? 'danger' : 'warning';
        const iconText = type === 'danger' ? '⚠' : '⚠';
        const confirmText = type === 'danger' ? 'Eliminar' : 'Confirmar';
        const confirmClass = type === 'danger' ? 'confirm' : 'warning';
        
        modal.innerHTML = `
            <div class="confirmation-modal-content">
                <div class="confirmation-modal-icon ${iconClass}">${iconText}</div>
                <div class="confirmation-modal-title">${title}</div>
                <div class="confirmation-modal-message">${message}</div>
                <div class="confirmation-modal-buttons">
                    <button class="confirmation-modal-btn cancel" data-result="false">Cancelar</button>
                    <button class="confirmation-modal-btn ${confirmClass}" data-result="true">${confirmText}</button>
                </div>
            </div>
        `;
        
        // Agregar al DOM
        document.body.appendChild(modal);
        
        // Mostrar con animación
        setTimeout(() => {
            modal.classList.add('show');
        }, 10);
        
        // Manejar clics en botones
        const handleClick = (e) => {
            const result = e.target.getAttribute('data-result') === 'true';
            modal.classList.remove('show');
            
            setTimeout(() => {
                document.body.removeChild(modal);
                resolve(result);
            }, 300);
        };
        
        // Agregar event listeners
        modal.querySelectorAll('.confirmation-modal-btn').forEach(btn => {
            btn.addEventListener('click', handleClick);
        });
        
        // Cerrar con ESC
        const handleKeydown = (e) => {
            if (e.key === 'Escape') {
                modal.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(modal);
                    resolve(false);
                }, 300);
                document.removeEventListener('keydown', handleKeydown);
            }
        };
        
        document.addEventListener('keydown', handleKeydown);
        
        // Cerrar al hacer clic fuera del modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
                setTimeout(() => {
                    document.body.removeChild(modal);
                    resolve(false);
                }, 300);
            }
        });
    });
}

// Exportar para uso en módulos
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { NotificationSystem, notificationSystem, showNotification, showAlert, showSessionMessage, showConfirmation };
}
