{{-- Componente centralizado para scripts del carrito y favoritos --}}
<script>
// Funciones globales centralizadas para el carrito y favoritos

/**
 * Función global para agregar productos al carrito
 * @param {number} productId - ID del producto
 * @param {number} quantity - Cantidad (opcional, por defecto 1)
 */
function addToCart(productId, quantity = 1) {
    const button = document.getElementById(`add-cart-btn-${productId}`);
    window.cartManager.addToCart(productId, quantity, button);
}

/**
 * Función global para manejar favoritos
 * @param {number} productId - ID del producto
 */
function toggleFavorites(productId) {
    // Verificar si el usuario está autenticado
    @guest
        // Si no está autenticado, redirigir al login
        window.location.href = '{{ route("login") }}';
        return;
    @endguest

    fetch('{{ route("wishlist.toggle") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.cartManager.showNotification(data.message, 'success');
            updateWishlistCount();
            // Cambiar el icono a favorito lleno
            const btn = document.getElementById(`favorites-btn-${productId}`);
            if (btn) {
                const img = btn.querySelector('img');
                if (data.is_in_wishlist) {
                    img.src = '{{ asset("images/favoritos.png") }}';
                    btn.title = 'Eliminar de favoritos';
                    btn.classList.add('active');
                } else {
                    img.src = '{{ asset("images/favoritos.png") }}';
                    btn.title = 'Agregar a favoritos';
                    btn.classList.remove('active');
                }
            }
        } else if (data.redirect) {
            // Redirigir al login si no está autenticado
            window.location.href = data.redirect;
        } else {
            window.cartManager.showNotification(data.error || 'Error al actualizar favoritos', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.cartManager.showNotification('Error al actualizar favoritos', 'error');
    });
}

/**
 * Función para actualizar contador de favoritos
 */
function updateWishlistCount() {
    @auth
        fetch('{{ route("wishlist.count") }}', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            const wishlistCount = document.getElementById('wishlist-count');
            if (wishlistCount) {
                wishlistCount.textContent = data.count;
            }
        })
        .catch(error => {
            console.error('Error updating wishlist count:', error);
        });
    @endauth
}

/**
 * Función para mostrar notificaciones (usando sistema unificado)
 * @param {string} message - Mensaje a mostrar
 * @param {string} type - Tipo de notificación (success, error, info)
 */
function showNotification(message, type) {
    window.cartManager.showNotification(message, type);
}

/**
 * Función para actualizar contador del carrito
 */
function updateCartCounter() {
    return window.cartManager.updateCartCounter();
}

/**
 * Función para actualizar contador del carrito (alias)
 */
function updateCartCount() {
    return window.cartManager.updateCartCounter();
}
</script>




