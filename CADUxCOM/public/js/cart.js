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
        
        // Actualizar contador automáticamente cada 30 segundos
        setInterval(() => {
            this.updateCartCounter();
        }, 30000);
        
        // Actualizar contador cuando la página vuelve a estar visible
        document.addEventListener('visibilitychange', () => {
            if (!document.hidden) {
                this.updateCartCounter();
            }
        });
        
        // Actualizar contador cuando se enfoca la ventana
        window.addEventListener('focus', () => {
            this.updateCartCounter();
        });
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
            // Obtener token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value;
            
            if (!csrfToken) {
                console.error('CSRF token not found');
                this.showNotification('Error de seguridad. Recarga la página.', 'error');
                return false;
            }

            const response = await fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `product_id=${productId}&quantity=${quantity}`
            });

            if (!response.ok) {
                // Si es un error 401 (no autenticado), redirigir al login
                if (response.status === 401) {
                    this.showNotification('Debes iniciar sesión para agregar productos al carrito', 'error');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                    return false;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            

            if (data.success) {
                this.showNotification(data.message || 'Producto agregado al carrito', 'success');
                this.updateCartCounter();
                this.animateCartIcon(button);
                return true;
            } else {
                // Manejar diferentes tipos de errores
                let errorMessage = 'Error al agregar el producto';
                if (data.error) {
                    errorMessage = data.error;
                } else if (data.message) {
                    errorMessage = data.message;
                }
                
                // Si es un error de autenticación, redirigir al login
                if (data.redirect && data.redirect.includes('login')) {
                    this.showNotification('Debes iniciar sesión para agregar productos al carrito', 'error');
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 2000);
                } else {
                    this.showNotification(errorMessage, 'error');
                }
                return false;
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión. Intenta nuevamente.', 'error');
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
            
            // Si la respuesta no es exitosa, no hacer nada (evitar errores en consola)
            if (!response.ok) {
                return;
            }
            
            const data = await response.json();
            const count = data.count || 0;
            const displayCount = count > 99 ? '99+' : count;
            
            // Actualizar el nuevo componente cart-counter
            const cartBadge = document.querySelector('.cart-badge');
            if (cartBadge) {
                cartBadge.textContent = displayCount;
                
                // Mostrar/ocultar badge
                if (count > 0) {
                    cartBadge.style.display = 'flex';
                    // Agregar animación de actualización
                    cartBadge.classList.add('update');
                    setTimeout(() => {
                        cartBadge.classList.remove('update');
                    }, 500);
                } else {
                    cartBadge.style.display = 'none';
                }
            }
            
            // Mantener compatibilidad con elementos antiguos
            const cartCountElements = document.querySelectorAll('.cart-count');
            cartCountElements.forEach(element => {
                element.textContent = displayCount;
                element.style.display = count > 0 ? 'flex' : 'none';
            });

            // También actualizar elementos con ID específico
            const cartCountById = document.getElementById('cart-count');
            if (cartCountById) {
                cartCountById.textContent = displayCount;
                cartCountById.style.display = count > 0 ? 'block' : 'none';
            }

            // Actualizar elementos con clase wishlist-count si existen
            const wishlistCount = document.getElementById('wishlist-count');
            if (wishlistCount) {
                // Solo actualizar si el usuario está autenticado
                const wishlistUrl = wishlistCount.dataset.url;
                if (wishlistUrl) {
                    fetch(wishlistUrl)
                        .then(response => response.json())
                        .then(data => {
                            if (data.count > 0) {
                                wishlistCount.textContent = data.count;
                                wishlistCount.style.display = 'flex';
                            } else {
                                wishlistCount.style.display = 'none';
                            }
                            wishlistCount.classList.add('update');
                            setTimeout(() => wishlistCount.classList.remove('update'), 500);
                        })
                        .catch(error => {
                            console.log('Wishlist counter not available');
                        });
                }
            }

        } catch (error) {
            // Silenciar errores del contador del carrito para evitar problemas en el login
            console.log('Cart counter not available (user not authenticated)');
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
     * Mostrar modal de confirmación personalizado
     * @param {string} title - Título del modal
     * @param {string} message - Mensaje del modal
     * @param {function} onConfirm - Función a ejecutar al confirmar
     * @param {function} onCancel - Función a ejecutar al cancelar (opcional)
     */
    showConfirmModal(title, message, onConfirm, onCancel = null) {
        // Crear contenedor del modal si no existe
        let modalContainer = document.getElementById('modal-container');
        if (!modalContainer) {
            modalContainer = document.createElement('div');
            modalContainer.id = 'modal-container';
            modalContainer.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                z-index: 20000;
                display: flex;
                align-items: center;
                justify-content: center;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                pointer-events: none;
            `;
            document.body.appendChild(modalContainer);
        }

        // Crear overlay de fondo
        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        overlay.style.cssText = `
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        `;

        // Crear modal
        const modal = document.createElement('div');
        modal.className = 'custom-modal';
        modal.style.cssText = `
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8f5f8 100%);
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(73, 135, 78, 0.3);
            max-width: 400px;
            width: 90%;
            max-height: 90vh;
            overflow: hidden;
            transform: scale(0.9) translateY(20px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(73, 135, 78, 0.1);
        `;

        modal.innerHTML = `
            <div class="modal-header" style="
                padding: 24px 24px 16px 24px;
                border-bottom: 1px solid rgba(73, 135, 78, 0.1);
                background: linear-gradient(135deg, #49874E 0%, #90D575 100%);
                color: white;
                text-align: center;
            ">
                <h3 style="
                    margin: 0;
                    font-size: 18px;
                    font-weight: 600;
                    color: white;
                ">${title}</h3>
            </div>
            
            <div class="modal-body" style="
                padding: 24px;
                text-align: center;
            ">
                <p style="
                    margin: 0 0 24px 0;
                    color: #333333;
                    font-size: 15px;
                    line-height: 1.5;
                ">${message}</p>
                
                <div class="modal-actions" style="
                    display: flex;
                    gap: 12px;
                    justify-content: center;
                    flex-wrap: wrap;
                ">
                    <button class="modal-btn modal-btn-cancel" style="
                        padding: 12px 24px;
                        border: 2px solid #666666;
                        background: transparent;
                        color: #666666;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 14px;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        min-width: 100px;
                    ">
                        Cancelar
                    </button>
                    
                    <button class="modal-btn modal-btn-confirm" style="
                        padding: 12px 24px;
                        border: 2px solid #49874E;
                        background: #49874E;
                        color: white;
                        border-radius: 8px;
                        font-weight: 600;
                        font-size: 14px;
                        cursor: pointer;
                        transition: all 0.2s ease;
                        min-width: 100px;
                    ">
                        Aceptar
                    </button>
                </div>
            </div>
        `;

        // Agregar elementos al contenedor
        modalContainer.innerHTML = '';
        modalContainer.appendChild(overlay);
        modalContainer.appendChild(modal);

        // Mostrar modal con animación
        requestAnimationFrame(() => {
            modalContainer.style.opacity = '1';
            modalContainer.style.visibility = 'visible';
            modalContainer.style.pointerEvents = 'auto';
            modal.style.transform = 'scale(1) translateY(0)';
        });

        // Event listeners
        const cancelBtn = modal.querySelector('.modal-btn-cancel');
        const confirmBtn = modal.querySelector('.modal-btn-confirm');

        const closeModal = () => {
            modalContainer.style.opacity = '0';
            modalContainer.style.visibility = 'hidden';
            modalContainer.style.pointerEvents = 'none';
            modal.style.transform = 'scale(0.9) translateY(20px)';
            
            setTimeout(() => {
                if (modalContainer.parentElement) {
                    modalContainer.remove();
                }
            }, 300);
        };

        cancelBtn.addEventListener('click', () => {
            closeModal();
            if (onCancel) onCancel();
        });

        confirmBtn.addEventListener('click', () => {
            closeModal();
            if (onConfirm) onConfirm();
        });

        overlay.addEventListener('click', () => {
            closeModal();
            if (onCancel) onCancel();
        });

        // Efectos hover para botones
        cancelBtn.addEventListener('mouseenter', () => {
            cancelBtn.style.background = '#666666';
            cancelBtn.style.color = 'white';
            cancelBtn.style.transform = 'translateY(-2px)';
            cancelBtn.style.boxShadow = '0 4px 12px rgba(102, 102, 102, 0.3)';
        });

        cancelBtn.addEventListener('mouseleave', () => {
            cancelBtn.style.background = 'transparent';
            cancelBtn.style.color = '#666666';
            cancelBtn.style.transform = 'translateY(0)';
            cancelBtn.style.boxShadow = 'none';
        });

        confirmBtn.addEventListener('mouseenter', () => {
            confirmBtn.style.background = '#90D575';
            confirmBtn.style.borderColor = '#90D575';
            confirmBtn.style.transform = 'translateY(-2px)';
            confirmBtn.style.boxShadow = '0 4px 12px rgba(73, 135, 78, 0.4)';
        });

        confirmBtn.addEventListener('mouseleave', () => {
            confirmBtn.style.background = '#49874E';
            confirmBtn.style.borderColor = '#49874E';
            confirmBtn.style.transform = 'translateY(0)';
            confirmBtn.style.boxShadow = 'none';
        });

        // Cerrar con Escape
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                closeModal();
                if (onCancel) onCancel();
                document.removeEventListener('keydown', handleEscape);
            }
        };
        document.addEventListener('keydown', handleEscape);
    }

    /**
     * Mostrar notificación
     * @param {string} message - Mensaje a mostrar
     * @param {string} type - Tipo de notificación (success, error, info)
     */
    showNotification(message, type = 'info') {
        // Evitar duplicaciones: verificar si ya existe una notificación similar
        const existingNotifications = document.querySelectorAll('.notification');
        const isDuplicate = Array.from(existingNotifications).some(notif => {
            const messageElement = notif.querySelector('.notification-message');
            return messageElement && messageElement.textContent.trim() === message.trim();
        });

        if (isDuplicate) {
            console.log('Notificación duplicada evitada:', message);
            return;
        }

        // Usar el sistema de notificaciones principal si está disponible
        if (window.notificationSystem && window.notificationSystem.show) {
            window.notificationSystem.show(message, type);
            return;
        }

        // Fallback: crear notificación simple
        this.createSimpleNotification(message, type);
    }

    /**
     * Crear notificación simple como fallback
     * @param {string} message - Mensaje a mostrar
     * @param {string} type - Tipo de notificación
     */
    createSimpleNotification(message, type) {
        // Crear contenedor de notificaciones si no existe
        let notificationContainer = document.getElementById('notification-container');
        if (!notificationContainer) {
            notificationContainer = document.createElement('div');
            notificationContainer.id = 'notification-container';
            notificationContainer.style.cssText = `
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
            document.body.appendChild(notificationContainer);
        }

        // Crear elemento de notificación
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;

        // Iconos según el tipo
        const icons = {
            success: '✓',
            error: '✗',
            info: 'ℹ',
            warning: '⚠'
        };

        notification.innerHTML = `
            <div class="notification-content">
                <div class="notification-icon">${icons[type] || icons.info}</div>
                <div class="notification-body">
                    <span class="notification-message">${message}</span>
                </div>
                <button class="notification-close" onclick="this.closest('.notification').remove()">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="notification-progress"></div>
        `;

        // Estilos base de la notificación con paleta corporativa
        notification.style.cssText = `
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8f5f8 100%);
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(73, 135, 78, 0.15);
            border-left: 4px solid;
            transform: translateX(100%);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            opacity: 0;
            pointer-events: auto;
            overflow: hidden;
            backdrop-filter: blur(10px);
        `;

        // Colores corporativos de CADUxCOM
        const colors = {
            success: '#49874E',  // Verde corporativo principal
            error: '#EF4444',    // Rojo para errores (mantenido para legibilidad)
            info: '#AA5FC7',     // Morado corporativo
            warning: '#F59E0B'   // Naranja para advertencias (mantenido para legibilidad)
        };
        notification.style.borderLeftColor = colors[type] || colors.info;

        // Agregar al contenedor
        notificationContainer.appendChild(notification);

        // Animar entrada
        requestAnimationFrame(() => {
            notification.style.transform = 'translateX(0)';
            notification.style.opacity = '1';
        });

        // Barra de progreso
        const progressBar = notification.querySelector('.notification-progress');
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
}

// Crear instancia global solo si existe el componente del carrito
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('.cart-badge') || document.querySelector('.cart-count') || document.querySelector('[data-cart-counter]')) {
        window.cartManager = new CartManager();
    }
});

// Funciones globales para compatibilidad
window.addToCart = function(productId, quantity = 1) {
    if (window.cartManager) {
        const button = event?.target?.closest('button');
        return window.cartManager.addToCart(productId, quantity, button);
    }
    return Promise.resolve(false);
};

window.updateCartCounter = function() {
    if (window.cartManager) {
        return window.cartManager.updateCartCounter();
    }
    return Promise.resolve();
};

window.updateCartCount = function() {
    if (window.cartManager) {
        return window.cartManager.updateCartCounter();
    }
    return Promise.resolve();
};

// CSS para el spinner de carga, animaciones y notificaciones mejoradas
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
    
    /* Estilos mejorados para notificaciones */
    .notification-content {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px 20px;
        position: relative;
    }
    
    .notification-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        color: white;
        margin-top: 2px;
    }
    
    .notification-success .notification-icon {
        background: #49874E;
    }
    
    .notification-error .notification-icon {
        background: #EF4444;
    }
    
    .notification-info .notification-icon {
        background: #AA5FC7;
    }
    
    .notification-warning .notification-icon {
        background: #F59E0B;
    }
    
    .notification-body {
        flex: 1;
        min-width: 0;
    }
    
    .notification-message {
        color: #333333;
        font-size: 14px;
        font-weight: 500;
        line-height: 1.4;
        word-wrap: break-word;
        display: block;
    }
    
    .notification-close {
        flex-shrink: 0;
        background: none;
        border: none;
        color: #666666;
        cursor: pointer;
        padding: 4px;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
        margin-top: 2px;
    }
    
    .notification-close:hover {
        background-color: #f8f5f8;
        color: #333333;
    }
    
    .notification-close:active {
        transform: scale(0.95);
    }
    
    .notification-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        height: 3px;
        background: linear-gradient(90deg, transparent, currentColor, transparent);
        opacity: 0.3;
    }
    
    /* Responsive design para notificaciones */
    @media (max-width: 768px) {
        #notification-container {
            top: 80px !important;
            right: 16px !important;
            left: 16px !important;
            max-width: none !important;
        }
        
        .notification-content {
            padding: 14px 16px;
        }
        
        .notification-message {
            font-size: 13px;
        }
        
        .notification-icon {
            width: 20px;
            height: 20px;
            font-size: 12px;
        }
        
        .notification-close {
            width: 20px;
            height: 20px;
        }
    }
    
    @media (max-width: 480px) {
        #notification-container {
            top: 70px !important;
            right: 12px !important;
            left: 12px !important;
        }
        
        .notification-content {
            padding: 12px 14px;
            gap: 10px;
        }
        
        .notification-message {
            font-size: 12px;
        }
    }
    
    /* Animaciones para el carrito */
    .header-icon {
        transition: transform 0.3s ease, filter 0.3s ease;
    }
    
    .cart-count {
        transition: transform 0.3s ease;
    }
    
    /* Animación de entrada mejorada */
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    /* Efecto de hover para notificaciones con paleta corporativa */
    .notification:hover {
        transform: translateX(-4px);
        box-shadow: 0 12px 40px rgba(73, 135, 78, 0.2);
    }
    
    /* Animación de la barra de progreso */
    @keyframes progressBar {
        from {
            transform: scaleX(1);
        }
        to {
            transform: scaleX(0);
        }
    }
    
    /* Estilos responsivos para el modal */
    @media (max-width: 768px) {
        .custom-modal {
            max-width: 95% !important;
            margin: 20px !important;
        }
        
        .modal-header h3 {
            font-size: 16px !important;
        }
        
        .modal-body p {
            font-size: 14px !important;
        }
        
        .modal-actions {
            flex-direction: column !important;
            gap: 8px !important;
        }
        
        .modal-btn {
            width: 100% !important;
            min-width: auto !important;
        }
    }
    
    @media (max-width: 480px) {
        .custom-modal {
            max-width: 98% !important;
            margin: 10px !important;
        }
        
        .modal-header {
            padding: 20px 20px 12px 20px !important;
        }
        
        .modal-body {
            padding: 20px !important;
        }
        
        .modal-header h3 {
            font-size: 15px !important;
        }
        
        .modal-body p {
            font-size: 13px !important;
            margin-bottom: 20px !important;
        }
    }
`;
document.head.appendChild(style);
