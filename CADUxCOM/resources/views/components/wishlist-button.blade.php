@props(['productId', 'isInWishlist' => false, 'size' => 'normal'])

@auth
    <button 
        class="wishlist-btn {{ $isInWishlist ? 'active' : '' }} {{ $size === 'small' ? 'small' : '' }}" 
        data-product-id="{{ $productId }}"
        title="{{ $isInWishlist ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
    >
        <img 
            src="{{ asset($isInWishlist ? 'images/heart-filled-icon.svg' : 'images/heart-icon.svg') }}" 
            alt="Favoritos" 
            class="wishlist-icon {{ $isInWishlist ? 'active' : '' }}"
        >
    </button>
@else
    <button 
        class="wishlist-btn disabled {{ $size === 'small' ? 'small' : '' }}" 
        onclick="showLoginAlert()"
        title="Inicia sesión para agregar a favoritos"
    >
        <img 
            src="{{ asset('images/heart-icon.svg') }}" 
            alt="Favoritos" 
            class="wishlist-icon"
        >
    </button>
@endauth

<link rel="stylesheet" href="{{ asset('css/wishlist-button.css') }}">

<script>
// Sistema de debounce para toggleWishlist
let toggleDebounceTimer = null;
const TOGGLE_DEBOUNCE_DELAY = 500; // 500ms de debounce para toggle

function toggleWishlist(productId) {
    // Limpiar timer anterior si existe
    if (toggleDebounceTimer) {
        clearTimeout(toggleDebounceTimer);
    }
    
    // Aplicar debounce para evitar múltiples llamadas
    toggleDebounceTimer = setTimeout(() => {
        const button = document.querySelector(`[data-product-id="${productId}"]`);
        if (!button) return;
        
        const icon = button.querySelector('.wishlist-icon');
        if (!icon) return;
        
        // Verificar si ya está procesando
        if (button.dataset.processing === 'true') {
            console.log('Toggle ya en proceso, ignorando');
            return;
        }
        
        // Marcar como procesando
        button.dataset.processing = 'true';
        
        // Mostrar loading
        button.style.opacity = '0.6';
        button.style.pointerEvents = 'none';
        
        // Obtener token CSRF
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                         document.querySelector('input[name="_token"]')?.value;
        
        if (!csrfToken) {
            console.error('CSRF token not found');
            showNotification('Error de seguridad. Recarga la página.', 'error');
            button.style.opacity = '1';
            button.style.pointerEvents = 'auto';
            button.dataset.processing = 'false';
            return;
        }
        
        fetch('{{ route("wishlist.toggle") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                // Actualizar estado visual
                if (data.is_in_wishlist) {
                    button.classList.add('active');
                    icon.classList.add('active');
                    icon.src = "{{ asset('images/heart-filled-icon.svg') }}";
                    button.title = 'Quitar de favoritos';
                } else {
                    button.classList.remove('active');
                    icon.classList.remove('active');
                    icon.src = "{{ asset('images/heart-icon.svg') }}";
                    button.title = 'Agregar a favoritos';
                }
                
                // Actualizar contador en header
                updateWishlistCount(data.wishlist_count);
                
                // Mostrar notificación con animación mejorada
                showWishlistNotification(data.message, data.is_in_wishlist ? 'added' : 'removed');
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error al actualizar favoritos', 'error');
        })
        .finally(() => {
            // Restaurar estado del botón
            button.style.opacity = '1';
            button.style.pointerEvents = 'auto';
            button.dataset.processing = 'false';
        });
    }, TOGGLE_DEBOUNCE_DELAY);
}

function showLoginAlert() {
    // Usar el sistema unificado de notificaciones si está disponible
    if (window.cartManager && window.cartManager.showNotification) {
        window.cartManager.showNotification(
            '🔐 Inicia sesión para agregar productos a tu lista de favoritos', 
            'info'
        );
        
        // Agregar vibración táctil en dispositivos móviles
        if (navigator.vibrate) {
            navigator.vibrate([100, 50, 100]);
        }
        
        return;
    }
    
    // Fallback: notificación personalizada mejorada
    showWishlistNotification('🔐 Inicia sesión para agregar productos a favoritos', 'login');
}

function updateWishlistCount(count) {
    const countElement = document.getElementById('wishlist-count');
    if (countElement) {
        countElement.textContent = count;
    }
    
    // También actualizar el contador del header si existe la función
    if (window.updateWishlistCountHeader) {
        window.updateWishlistCountHeader();
    }
}

// Sistema de debounce para notificaciones
let notificationDebounceTimer = null;
const NOTIFICATION_DEBOUNCE_DELAY = 100; // 100ms de debounce para notificaciones

function showWishlistNotification(message, type = 'info') {
    // Limpiar timer anterior si existe
    if (notificationDebounceTimer) {
        clearTimeout(notificationDebounceTimer);
    }
    
    // Aplicar debounce para evitar notificaciones duplicadas
    notificationDebounceTimer = setTimeout(() => {
        // Verificar si ya existe una notificación similar
        const existingNotifications = document.querySelectorAll('.notification, .wishlist-notification');
        const isDuplicate = Array.from(existingNotifications).some(notif => {
            const messageElement = notif.querySelector('.notification-message, .wishlist-notification-message');
            return messageElement && messageElement.textContent.trim() === message.trim();
        });

        if (isDuplicate) {
            console.log('Notificación duplicada evitada:', message);
            return;
        }

        // Usar el sistema unificado de notificaciones si está disponible
        if (window.cartManager && window.cartManager.showNotification) {
            // Convertir tipos específicos de favoritos a tipos estándar
            let notificationType = type;
            if (type === 'added') {
                notificationType = 'success';
            } else if (type === 'removed') {
                notificationType = 'info';
            } else if (type === 'login') {
                notificationType = 'info';
            }
            
            window.cartManager.showNotification(message, notificationType);
            return;
        }
        
        // Fallback: usar el sistema de notificaciones principal si está disponible
        if (window.notificationSystem && window.notificationSystem.show) {
            let notificationType = type;
            if (type === 'added') {
                notificationType = 'success';
            } else if (type === 'removed') {
                notificationType = 'info';
            } else if (type === 'login') {
                notificationType = 'info';
            }
            
            window.notificationSystem.show(message, notificationType);
            return;
        }
        
        // Fallback final: notificación simple sin duplicación
        console.log(`Wishlist notification: ${message} (${type})`);
    }, NOTIFICATION_DEBOUNCE_DELAY);
}

function showNotification(message, type = 'info') {
    // Redirigir al nuevo sistema de notificaciones
    showWishlistNotification(message, type);
}

// Agregar estilos de animación si no existen
if (!document.querySelector('#notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

// Función para cargar el estado inicial de los botones de favoritos
function loadWishlistStatus() {
    const buttons = document.querySelectorAll('.wishlist-btn[data-product-id]');
    if (buttons.length === 0) return;
    
    const productIds = Array.from(buttons).map(btn => btn.dataset.productId);
    
    // Obtener token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    if (!csrfToken) return;
    
    // Verificar estado de cada producto
    productIds.forEach(productId => {
        fetch(`{{ route("wishlist.status") }}?product_id=${productId}`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const button = document.querySelector(`[data-product-id="${productId}"]`);
            const icon = button?.querySelector('.wishlist-icon');
            
            if (button && icon) {
                if (data.is_in_wishlist) {
                    button.classList.add('active');
                    icon.classList.add('active');
                    icon.src = "{{ asset('images/heart-filled-icon.svg') }}";
                    button.title = 'Quitar de favoritos';
                } else {
                    button.classList.remove('active');
                    icon.classList.remove('active');
                    icon.src = "{{ asset('images/heart-icon.svg') }}";
                    button.title = 'Agregar a favoritos';
                }
            }
        })
        .catch(error => {
            console.error('Error loading wishlist status:', error);
        });
    });
}

// Sistema de debounce para evitar múltiples llamadas
let wishlistDebounceTimer = null;
const WISHLIST_DEBOUNCE_DELAY = 300; // 300ms de debounce

// Cargar estado cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    loadWishlistStatus();
    
    // Event listeners para botones de wishlist con debounce
    document.querySelectorAll('.wishlist-btn[data-product-id]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Limpiar timer anterior si existe
            if (wishlistDebounceTimer) {
                clearTimeout(wishlistDebounceTimer);
            }
            
            // Aplicar debounce
            wishlistDebounceTimer = setTimeout(() => {
                const productId = this.getAttribute('data-product-id');
                if (this.classList.contains('disabled')) {
                    showLoginAlert();
                } else {
                    toggleWishlist(productId);
                }
            }, WISHLIST_DEBOUNCE_DELAY);
        });
    });
});
</script>
