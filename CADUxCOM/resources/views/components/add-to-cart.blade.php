@props(['product'])

<style>
/* Estilos específicos para el botón de agregar al carrito */
#add-cart-btn-{{ $product->Id_Producto }} {
    background: linear-gradient(135deg, #90D575, #49874E) !important;
    border: none !important;
    box-shadow: 0 8px 25px rgba(144, 213, 117, 0.4) !important;
    position: relative !important;
    overflow: hidden !important;
}

#add-cart-btn-{{ $product->Id_Producto }}:hover {
    background: linear-gradient(135deg, #49874E, #90D575) !important;
    transform: translateY(-3px) !important;
    box-shadow: 0 12px 35px rgba(144, 213, 117, 0.6) !important;
}

#add-cart-btn-{{ $product->Id_Producto }}:active {
    transform: translateY(-1px) !important;
}

#add-cart-btn-{{ $product->Id_Producto }}.animate::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    background: rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    transition: width 0.6s, height 0.6s;
}

#add-cart-btn-{{ $product->Id_Producto }}.animate::before {
    width: 300px;
    height: 300px;
}

#add-cart-btn-{{ $product->Id_Producto }}.success {
    background: linear-gradient(135deg, #27ae60, #2ecc71) !important;
    transform: scale(1.05) !important;
}

#add-cart-btn-{{ $product->Id_Producto }}.success::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-weight: bold;
    font-size: 1.5rem;
    animation: checkmark 0.6s ease-in-out;
}

@keyframes checkmark {
    0% {
        opacity: 0;
        transform: translate(-50%, -50%) scale(0);
    }
    50% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1.2);
    }
    100% {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

/* Selector de cantidad mejorado */
.cart-qty-selector-{{ $product->Id_Producto }} {
    box-shadow: 0 4px 15px rgba(144, 213, 117, 0.3) !important;
    border: 2px solid #90D575 !important;
}

.cart-qty-btn-{{ $product->Id_Producto }}:hover {
    background: #90D575 !important;
    color: white !important;
    transform: scale(1.1) !important;
}
</style>

<div class="add-to-cart-container">
    <form id="add-to-cart-form-{{ $product->Id_Producto }}" method="POST" action="{{ route('cart.add') }}" class="flex items-center space-x-3">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->Id_Producto }}">
        
        <!-- Selector de cantidad -->
        <div class="flex items-center border-2 border-green-300 rounded-xl bg-white shadow-md cart-qty-selector-{{ $product->Id_Producto }}">
            <button type="button" onclick="decreaseCartQty({{ $product->Id_Producto }})" 
                    class="w-12 h-12 flex items-center justify-center hover:bg-green-500 hover:text-white rounded-l-xl text-green-600 font-bold text-xl transition-all duration-200 cart-qty-btn-{{ $product->Id_Producto }}">
                -
            </button>
            <input type="number" name="quantity" min="1" max="{{ $product->Cantidad }}" 
                   value="1" id="cart-qty-{{ $product->Id_Producto }}"
                   class="w-20 text-center border-0 focus:ring-0 font-bold text-green-700 text-lg bg-green-50">
            <button type="button" onclick="increaseCartQty({{ $product->Id_Producto }})" 
                    class="w-12 h-12 flex items-center justify-center hover:bg-green-500 hover:text-white rounded-r-xl text-green-600 font-bold text-xl transition-all duration-200 cart-qty-btn-{{ $product->Id_Producto }}">
                +
            </button>
        </div>

        <!-- Botón agregar al carrito mejorado -->
        <button type="submit" 
                class="bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 min-w-[250px] justify-center"
                id="add-cart-btn-{{ $product->Id_Producto }}">
            <span class="text-2xl">🛒</span>
            <span>Agregar al Carrito</span>
        </button>
    </form>

    <!-- Mensaje de stock mejorado -->
    @if($product->Cantidad <= 5 && $product->Cantidad > 0)
        <div class="mt-3 p-3 bg-orange-50 border-l-4 border-orange-400 rounded-r-lg">
            <p class="text-orange-700 text-sm font-medium">
                ⚠️ Solo quedan {{ $product->Cantidad }} unidades disponibles
            </p>
        </div>
    @elseif($product->Cantidad == 0)
        <div class="mt-3 p-3 bg-red-50 border-l-4 border-red-400 rounded-r-lg">
            <p class="text-red-700 text-sm font-medium">
                ❌ Producto agotado temporalmente
            </p>
        </div>
    @endif
</div>

<script>
function increaseCartQty(productId) {
    const input = document.getElementById(`cart-qty-${productId}`);
    const max = parseInt(input.getAttribute('max'));
    const current = parseInt(input.value);
    
    if (current < max) {
        input.value = current + 1;
    }
}

function decreaseCartQty(productId) {
    const input = document.getElementById(`cart-qty-${productId}`);
    const current = parseInt(input.value);
    
    if (current > 1) {
        input.value = current - 1;
    }
}

// Manejar envío del formulario usando cartManager + loader + notificaciones
document.getElementById('add-to-cart-form-{{ $product->Id_Producto }}').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const button = document.getElementById('add-cart-btn-{{ $product->Id_Producto }}');
    const originalText = button.innerHTML;
    const quantity = parseInt(document.getElementById(`cart-qty-{{ $product->Id_Producto }}`).value);

    // Mostrar loader en el botón con animación
    button.innerHTML = '<span class="animate-spin">⏳</span><span>Agregando...</span>';
    button.disabled = true;
    button.classList.add('animate');

    // Llamada a cartManager
    window.cartManager.addToCart({{ $product->Id_Producto }}, quantity, button)
    .then(success => {
        if (success) {
            // Usar el sistema de notificaciones mejorado
            if (window.showCartNotification) {
                window.showCartNotification('¡Producto agregado al carrito!', 3000);
            } else {
                showNotification('Producto agregado al carrito', 'success');
            }
            updateCartCount();
            document.getElementById(`cart-qty-{{ $product->Id_Producto }}`).value = 1;
            
            // Animación de éxito
            button.classList.add('success');
            setTimeout(() => {
                button.classList.remove('success');
            }, 1000);
        } else {
            if (window.showCartNotification) {
                window.showCartNotification('Error al agregar al carrito', 3000);
            } else {
                showNotification('Error al agregar al carrito', 'error');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (window.showCartNotification) {
            window.showCartNotification('Error al agregar al carrito', 3000);
        } else {
            showNotification('Error al agregar al carrito', 'error');
        }
    })
    .finally(() => {
        button.innerHTML = originalText;
        button.disabled = false;
        button.classList.remove('animate');
    });
});

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

function updateCartCount() {
    fetch('{{ route("cart.count") }}')
        .then(response => response.json())
        .then(data => {
            const cartCount = document.querySelector('.cart-count');
            if (cartCount) {
                cartCount.textContent = data.count;
                cartCount.style.display = data.count > 0 ? 'block' : 'none';
            }
        });
}
</script>
