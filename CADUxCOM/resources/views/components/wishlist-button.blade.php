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
function toggleWishlist(productId) {
    const button = document.querySelector(`[data-product-id="${productId}"]`);
    const icon = button.querySelector('.wishlist-icon');
    
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
    });
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
}

function showWishlistNotification(message, type = 'info') {
    // Usar el sistema unificado si está disponible
    if (window.cartManager && window.cartManager.showNotification && type !== 'added' && type !== 'removed' && type !== 'login') {
        window.cartManager.showNotification(message, type);
        return;
    }
    
    // Iconos descriptivos para cada tipo de acción
    const icons = {
        added: '💖',
        removed: '💔',
        login: '🔐',
        success: '✅',
        error: '❌',
        info: 'ℹ️'
    };
    
    // Colores corporativos
    const colors = {
        added: 'var(--color-green-dark)',
        removed: 'var(--color-purple)',
        login: 'var(--color-purple)',
        success: 'var(--color-green-dark)',
        error: '#e74c3c',
        info: 'var(--color-purple)'
    };
    
    // Crear contenedor de notificaciones si no existe
    let container = document.getElementById('wishlist-notification-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'wishlist-notification-container';
        container.style.cssText = `
            position: fixed;
            top: 100px;
            right: 20px;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 400px;
            width: 100%;
            pointer-events: none;
        `;
        document.body.appendChild(container);
    }
    
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `wishlist-notification wishlist-notification-${type}`;
    
    notification.innerHTML = `
        <div class="wishlist-notification-content">
            <div class="wishlist-notification-icon">${icons[type] || icons.info}</div>
            <div class="wishlist-notification-body">
                <span class="wishlist-notification-message">${message}</span>
            </div>
            <button class="wishlist-notification-close" onclick="this.closest('.wishlist-notification').remove()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
        <div class="wishlist-notification-progress"></div>
    `;
    
    // Estilos base
    notification.style.cssText = `
        position: relative;
        background: linear-gradient(135deg, #ffffff 0%, #f8f5f8 100%);
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(73, 135, 78, 0.15);
        border-left: 4px solid ${colors[type] || colors.info};
        transform: translateX(100%);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        opacity: 0;
        pointer-events: auto;
        overflow: hidden;
        backdrop-filter: blur(10px);
    `;
    
    // Agregar al contenedor
    container.appendChild(notification);
    
    // Animar entrada
    requestAnimationFrame(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    });
    
    // Barra de progreso
    const progressBar = notification.querySelector('.wishlist-notification-progress');
    progressBar.style.cssText = `
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: ${colors[type] || colors.info};
        width: 100%;
        transform: scaleX(1);
        transform-origin: left;
        transition: transform 4s linear;
    `;
    
    // Iniciar animación de la barra de progreso
    requestAnimationFrame(() => {
        progressBar.style.transform = 'scaleX(0)';
    });
    
    // Feedback táctil para dispositivos móviles
    if (navigator.vibrate) {
        if (type === 'added') {
            navigator.vibrate([50, 30, 50]); // Vibración suave para agregar
        } else if (type === 'removed') {
            navigator.vibrate([100]); // Vibración simple para remover
        } else if (type === 'login') {
            navigator.vibrate([100, 50, 100]); // Vibración de alerta
        }
    }
    
    // Auto-remover después de 4 segundos
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 400);
    }, 4000);
    
    // Hover para pausar la animación
    notification.addEventListener('mouseenter', () => {
        progressBar.style.animationPlayState = 'paused';
    });
    
    notification.addEventListener('mouseleave', () => {
        progressBar.style.animationPlayState = 'running';
    });
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

// Cargar estado cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    loadWishlistStatus();
    
    // Event listeners para botones de wishlist
    document.querySelectorAll('.wishlist-btn[data-product-id]').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (this.classList.contains('disabled')) {
                showLoginAlert();
            } else {
                toggleWishlist(productId);
            }
        });
    });
});
</script>
