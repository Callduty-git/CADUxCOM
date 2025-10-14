// === SISTEMA DE ANIMACIONES DEL CARRITO ===
class CartAnimationManager {
    constructor() {
        this.init();
    }

    init() {
        // Cargar estilos de animación
        this.loadAnimationStyles();
        
        // Event listeners para botones de agregar al carrito
        this.bindCartButtons();
        
        // Event listeners para el contador del carrito
        this.bindCartCounter();
    }

    loadAnimationStyles() {
        if (!document.querySelector('#cart-animations-styles')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = '/css/cart-animations.css';
            link.id = 'cart-animations-styles';
            document.head.appendChild(link);
        }
    }

    bindCartButtons() {
        document.addEventListener('click', (e) => {
            const cartButton = e.target.closest('.add-to-cart-btn, .btn-cart-form button, [data-product-id]');
            if (cartButton && cartButton.textContent.includes('Agregar')) {
                this.animateCartButton(cartButton);
            }
        });
    }

    bindCartCounter() {
        // Observar cambios en el contador del carrito
        const cartCounter = document.getElementById('cart-count');
        if (cartCounter) {
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.type === 'childList' || mutation.type === 'characterData') {
                        this.animateCartCounter();
                    }
                });
            });
            observer.observe(cartCounter, { childList: true, characterData: true, subtree: true });
        }
    }

    animateCartButton(button) {
        // Agregar clase de animación
        button.classList.add('animate');
        
        // Simular éxito después de un breve delay
        setTimeout(() => {
            button.classList.remove('animate');
            button.classList.add('success');
            
            // Restaurar estado original
            setTimeout(() => {
                button.classList.remove('success');
            }, 1000);
        }, 200);
    }

    animateCartCounter() {
        const cartCounter = document.getElementById('cart-count');
        if (cartCounter) {
            cartCounter.classList.add('cart-count-animate');
            setTimeout(() => {
                cartCounter.classList.remove('cart-count-animate');
            }, 600);
        }
    }

    showCartNotification(message = 'Producto agregado al carrito', duration = 3000) {
        // Remover notificación existente
        const existingNotification = document.querySelector('.cart-notification');
        if (existingNotification) {
            existingNotification.remove();
        }

        // Crear nueva notificación
        const notification = document.createElement('div');
        notification.className = 'cart-notification';
        notification.innerHTML = `
            <div class="cart-notification-icon">🛒</div>
            <div class="cart-notification-message">${message}</div>
            <button class="cart-notification-close" onclick="this.parentElement.remove()">×</button>
        `;

        document.body.appendChild(notification);

        // Mostrar con animación
        setTimeout(() => {
            notification.classList.add('show');
        }, 100);

        // Auto-ocultar
        setTimeout(() => {
            notification.classList.add('hide');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 400);
        }, duration);
    }

    animateProductFly(productImage, targetElement) {
        if (!productImage || !targetElement) return;

        // Crear elemento de vuelo
        const flyElement = document.createElement('div');
        flyElement.className = 'product-fly-animation';
        
        const img = document.createElement('img');
        img.src = productImage.src;
        img.alt = productImage.alt;
        flyElement.appendChild(img);

        // Posicionar en la imagen del producto
        const productRect = productImage.getBoundingClientRect();
        flyElement.style.left = productRect.left + 'px';
        flyElement.style.top = productRect.top + 'px';
        flyElement.style.width = productRect.width + 'px';
        flyElement.style.height = productRect.height + 'px';

        document.body.appendChild(flyElement);

        // Animar hacia el carrito
        const targetRect = targetElement.getBoundingClientRect();
        setTimeout(() => {
            flyElement.style.left = targetRect.left + 'px';
            flyElement.style.top = targetRect.top + 'px';
            flyElement.style.width = '20px';
            flyElement.style.height = '20px';
            flyElement.style.opacity = '0';
        }, 100);

        // Remover elemento después de la animación
        setTimeout(() => {
            if (flyElement.parentElement) {
                flyElement.remove();
            }
        }, 900);
    }

    // Método público para mostrar notificación personalizada
    showNotification(message, type = 'success') {
        this.showCartNotification(message);
    }

    // Método público para animar vuelo de producto
    flyProductToCart(productImage, cartElement) {
        this.animateProductFly(productImage, cartElement);
    }
}

// Inicializar el sistema de animaciones
document.addEventListener('DOMContentLoaded', () => {
    window.cartAnimationManager = new CartAnimationManager();
});

// Función global para mostrar notificaciones del carrito
window.showCartNotification = function(message, duration = 3000) {
    if (window.cartAnimationManager) {
        window.cartAnimationManager.showCartNotification(message, duration);
    }
};

// Función global para animar vuelo de producto
window.flyProductToCart = function(productImage, cartElement) {
    if (window.cartAnimationManager) {
        window.cartAnimationManager.flyProductToCart(productImage, cartElement);
    }
};


