/**
 * Sistema unificado de carrito de compras
 * Maneja todas las operaciones del carrito de forma consistente
 */

class CartManager {
    constructor() {
        this.init();
    }

    init() {
        // Cargar contador inicial
        this.updateCartCounter();
    }

    /**
     * Agregar producto al carrito
     * @param {number} productId - ID del producto
     * @param {number} quantity - Cantidad (opcional, por defecto 1)
     * @param {HTMLElement} button - Botón que activó la acción (opcional)
     */
    async addToCart(productId, quantity = 1, button = null) {
        if (button) {
            this.setButtonLoading(button, true);
        }

        try {
            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: `product_id=${productId}&quantity=${quantity}`
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Producto agregado al carrito', 'success');
                this.updateCartCounter();
                this.animateCartIcon(button);
                return true;
            } else {
                this.showNotification(data.error || 'Error al agregar el producto', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error al agregar el producto', 'error');
            return false;
        } finally {
            if (button) {
                this.setButtonLoading(button, false);
            }
        }
    }

    /**
     * Actualizar cantidad de producto en el carrito
     * @param {number} productId - ID del producto
     * @param {number} quantity - Nueva cantidad
     */
    async updateCartItem(productId, quantity) {
        try {
            const response = await fetch('/cart/update', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: `product_id=${productId}&quantity=${quantity}`
            });

            const data = await response.json();

            if (data.success) {
                this.updateCartCounter();
                return true;
            } else {
                this.showNotification(data.error || 'Error al actualizar el producto', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error al actualizar el producto', 'error');
            return false;
        }
    }

    /**
     * Remover producto del carrito
     * @param {number} productId - ID del producto
     */
    async removeFromCart(productId) {
        try {
            const response = await fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: `product_id=${productId}`
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Producto eliminado del carrito', 'success');
                this.updateCartCounter();
                return true;
            } else {
                this.showNotification(data.error || 'Error al eliminar el producto', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error al eliminar el producto', 'error');
            return false;
        }
    }

    /**
     * Limpiar todo el carrito
     */
    async clearCart() {
        try {
            const response = await fetch('/cart/clear', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showNotification('Carrito vaciado', 'success');
                this.updateCartCounter();
                return true;
            } else {
                this.showNotification(data.error || 'Error al vaciar el carrito', 'error');
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error al vaciar el carrito', 'error');
            return false;
        }
    }

    /**
     * Actualizar contador del carrito en el header
     */
    async updateCartCounter() {
        try {
            const response = await fetch('/cart/count');
            const data = await response.json();
            
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = data.count;
                element.style.display = data.count > 0 ? 'flex' : 'none';
            });

            // También actualizar elementos con ID específico
            const cartCountById = document.getElementById('cart-count');
            if (cartCountById) {
                cartCountById.textContent = data.count;
                cartCountById.style.display = data.count > 0 ? 'block' : 'none';
            }

        } catch (error) {
            console.error('Error updating cart counter:', error);
        }
    }

    /**
     * Establecer estado de carga en un botón
     * @param {HTMLElement} button - Botón a modificar
     * @param {boolean} loading - Si está cargando o no
     */
    setButtonLoading(button, loading) {
        if (loading) {
            button.dataset.originalText = button.innerHTML;
            button.innerHTML = '<span class="loading-spinner"></span> Agregando...';
            button.disabled = true;
            
            // Animación de pulso mientras carga
            button.style.animation = 'pulse 1s infinite';
        } else {
            button.innerHTML = button.dataset.originalText || button.innerHTML;
            button.disabled = false;
            button.style.animation = '';
            
            // Animación de éxito
            button.style.transform = 'scale(1.05)';
            button.style.transition = 'transform 0.2s ease';
            setTimeout(() => {
                button.style.transform = 'scale(1)';
            }, 200);
        }
    }

    /**
     * Animar el ícono del carrito cuando se agrega un producto
     * @param {HTMLElement} button - Botón que activó la acción (opcional)
     */
    animateCartIcon(button = null) {
        const cartIcon = document.querySelector('img[alt="Carrito"]');
        const cartCount = document.querySelector('#cart-count');
        
        if (cartIcon) {
            // Efecto de rebote más pronunciado
            cartIcon.style.transform = 'scale(1.3)';
            cartIcon.style.transition = 'transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            
            // Efecto de rebote
            setTimeout(() => {
                cartIcon.style.transform = 'scale(1)';
            }, 200);
            
            // Efecto de brillo y rotación
            cartIcon.style.filter = 'brightness(1.4) saturate(1.2)';
            cartIcon.style.transform += ' rotate(5deg)';
            setTimeout(() => {
                cartIcon.style.filter = 'brightness(1) saturate(1)';
                cartIcon.style.transform = 'scale(1) rotate(0deg)';
            }, 400);
        }
        
        if (cartCount) {
            // Animar el contador con efecto más visible
            cartCount.style.transform = 'scale(1.5)';
            cartCount.style.transition = 'transform 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
            cartCount.style.background = 'linear-gradient(135deg, #FF6B6B 0%, #AA5FC7 100%)';
            
            setTimeout(() => {
                cartCount.style.transform = 'scale(1)';
                cartCount.style.background = 'linear-gradient(135deg, #AA5FC7 0%, #8B5CF6 100%)';
            }, 300);
        }
        
        // Crear partículas que vuelen hacia el carrito
        this.createFlyingParticles(button);
    }

    /**
     * Crear partículas que vuelen hacia el carrito
     * @param {HTMLElement} button - Botón que activó la acción (opcional)
     */
    createFlyingParticles(button = null) {
        const cartIcon = document.querySelector('img[alt="Carrito"]');
        if (!cartIcon) return;

        const cartRect = cartIcon.getBoundingClientRect();
        const cartX = cartRect.left + cartRect.width / 2;
        const cartY = cartRect.top + cartRect.height / 2;

        // Crear 5 partículas más visibles
        for (let i = 0; i < 5; i++) {
            const particle = document.createElement('div');
            particle.style.cssText = `
                position: fixed;
                width: 12px;
                height: 12px;
                background: linear-gradient(135deg, #AA5FC7 0%, #8B5CF6 100%);
                border-radius: 50%;
                pointer-events: none;
                z-index: 10000;
                box-shadow: 0 4px 12px rgba(170, 95, 199, 0.8);
                border: 2px solid rgba(255, 255, 255, 0.3);
            `;

            // Posición inicial desde el botón o centro de la pantalla
            let startX, startY;
            if (button) {
                const buttonRect = button.getBoundingClientRect();
                startX = buttonRect.left + buttonRect.width / 2 + (Math.random() - 0.5) * 40;
                startY = buttonRect.top + buttonRect.height / 2 + (Math.random() - 0.5) * 40;
            } else {
                startX = window.innerWidth / 2 + (Math.random() - 0.5) * 300;
                startY = window.innerHeight / 2 + (Math.random() - 0.5) * 300;
            }
            
            particle.style.left = startX + 'px';
            particle.style.top = startY + 'px';

            document.body.appendChild(particle);

            // Animar hacia el carrito con efecto más visible
            particle.animate([
                {
                    transform: 'translate(0, 0) scale(1)',
                    opacity: 1
                },
                {
                    transform: `translate(${cartX - startX}px, ${cartY - startY}px) scale(0.5)`,
                    opacity: 0.8
                },
                {
                    transform: `translate(${cartX - startX}px, ${cartY - startY}px) scale(0.2)`,
                    opacity: 0
                }
            ], {
                duration: 1000,
                easing: 'cubic-bezier(0.25, 0.46, 0.45, 0.94)',
                delay: i * 80
            }).onfinish = () => {
                particle.remove();
            };
        }
    }

    /**
     * Mostrar notificación
     * @param {string} message - Mensaje a mostrar
     * @param {string} type - Tipo de notificación (success, error, info)
     */
    showNotification(message, type = 'info') {
        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-message">${message}</span>
                <button class="notification-close" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        // Estilos de la notificación
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            padding: 16px 20px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            max-width: 400px;
            word-wrap: break-word;
        `;

        // Colores según el tipo
        const colors = {
            success: '#10B981',
            error: '#EF4444',
            info: '#3B82F6',
            warning: '#F59E0B'
        };
        notification.style.backgroundColor = colors[type] || colors.info;

        // Agregar al DOM
        document.body.appendChild(notification);

        // Animar entrada
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Auto-remover después de 4 segundos
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }
}

// Crear instancia global
window.cartManager = new CartManager();

// Funciones globales para compatibilidad
window.addToCart = function(productId, quantity = 1) {
    const button = event?.target?.closest('button');
    return window.cartManager.addToCart(productId, quantity, button);
};

window.updateCartCounter = function() {
    return window.cartManager.updateCartCounter();
};

window.updateCartCount = function() {
    return window.cartManager.updateCartCounter();
};

// CSS para el spinner de carga y animaciones
const style = document.createElement('style');
style.textContent = `
    .loading-spinner {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid #ffffff;
        border-radius: 50%;
        border-top-color: transparent;
        animation: spin 1s linear infinite;
        margin-right: 8px;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.7; }
    }
    
    .notification-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }
    
    .notification-close {
        background: none;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: background-color 0.2s;
    }
    
    .notification-close:hover {
        background-color: rgba(255, 255, 255, 0.2);
    }
    
    /* Animaciones para el carrito */
    .header-icon {
        transition: transform 0.3s ease, filter 0.3s ease;
    }
    
    .cart-count {
        transition: transform 0.3s ease;
    }
`;
document.head.appendChild(style);
