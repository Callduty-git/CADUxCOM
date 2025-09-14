@props(['productId', 'isInWishlist' => false, 'size' => 'normal'])

@auth
    <button 
        class="wishlist-btn {{ $isInWishlist ? 'active' : '' }} {{ $size === 'small' ? 'small' : '' }}" 
        data-product-id="{{ $productId }}"
        onclick="toggleWishlist({{ $productId }})"
        title="{{ $isInWishlist ? 'Quitar de favoritos' : 'Agregar a favoritos' }}"
    >
        <img 
            src="{{ asset('images/favoritos.png') }}" 
            alt="Favoritos" 
            class="wishlist-icon {{ $isInWishlist ? 'active' : '' }}"
        >
    </button>
@else
    <button 
        class="wishlist-btn disabled {{ $size === 'small' ? 'small' : '' }}" 
        onclick="showLoginMessage()"
        title="Inicia sesión para agregar a favoritos"
    >
        <img 
            src="{{ asset('images/favoritos.png') }}" 
            alt="Favoritos" 
            class="wishlist-icon"
        >
    </button>
@endauth

<style>
:root {
    --color-white: #FFFFFF;
    --color-green-dark: #49874E;
    --color-green-light: #90D575;
    --color-purple: #AA5FC7;
    --color-gray-light: #F8F9FA;
    --color-gray-medium: #6C757D;
    --color-gray-dark: #333333;
    --shadow-light: 0 2px 8px rgba(0, 0, 0, 0.1);
    --shadow-medium: 0 4px 16px rgba(0, 0, 0, 0.15);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.wishlist-btn {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 40px;
    height: 40px;
    background: rgba(255, 255, 255, 0.95);
    border: none;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: var(--transition);
    backdrop-filter: blur(10px);
    z-index: 10;
    box-shadow: var(--shadow-light);
}

.wishlist-btn.small {
    width: 28px;
    height: 28px;
    top: 8px;
    right: 8px;
}

.wishlist-btn:hover:not(.disabled) {
    background: var(--color-white);
    transform: scale(1.1);
    box-shadow: var(--shadow-medium);
}

.wishlist-btn.disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.wishlist-btn.disabled:hover {
    transform: none;
    background: rgba(255, 255, 255, 0.9);
    box-shadow: var(--shadow-light);
}

.wishlist-icon {
    width: 20px;
    height: 20px;
    filter: grayscale(1) brightness(0.7);
    transition: all 0.3s ease;
}

.wishlist-btn.small .wishlist-icon {
    width: 16px;
    height: 16px;
}

.wishlist-icon.active {
    filter: brightness(0) saturate(100%) invert(42%) sepia(93%) saturate(1352%) hue-rotate(234deg) brightness(119%) contrast(119%);
}

.wishlist-btn.active .wishlist-icon {
    filter: brightness(0) saturate(100%) invert(42%) sepia(93%) saturate(1352%) hue-rotate(234deg) brightness(119%) contrast(119%);
    transform: scale(1.1);
}

.wishlist-btn.active {
    background: var(--color-white);
    box-shadow: var(--shadow-medium);
}
</style>

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
                button.title = 'Quitar de favoritos';
            } else {
                button.classList.remove('active');
                icon.classList.remove('active');
                button.title = 'Agregar a favoritos';
            }
            
            // Actualizar contador en header
            updateWishlistCount(data.wishlist_count);
            
            // Mostrar notificación
            showNotification(data.message, 'success');
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

function showLoginMessage() {
    showNotification('Inicia sesión para agregar productos a favoritos', 'info');
}

function updateWishlistCount(count) {
    const countElement = document.getElementById('wishlist-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function showNotification(message, type = 'info') {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.textContent = message;
    
    // Agregar estilos
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 500;
        z-index: 10000;
        animation: slideIn 0.3s ease-out;
        max-width: 300px;
        font-family: 'Figtree', Arial, sans-serif;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    `;
    
    // Colores según el tipo
    if (type === 'success') {
        notification.style.backgroundColor = 'var(--color-green-dark)';
    } else if (type === 'error') {
        notification.style.backgroundColor = '#e74c3c';
    } else if (type === 'info') {
        notification.style.backgroundColor = 'var(--color-purple)';
    } else {
        notification.style.backgroundColor = 'var(--color-green-light)';
    }
    
    // Agregar al DOM
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease-in';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
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
                    button.title = 'Quitar de favoritos';
                } else {
                    button.classList.remove('active');
                    icon.classList.remove('active');
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
});
</script>
