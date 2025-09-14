@props(['product'])

<div class="add-to-cart-container">
    <form id="add-to-cart-form-{{ $product->Id_Producto }}" method="POST" action="{{ route('cart.add') }}" class="flex items-center space-x-3">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->Id_Producto }}">
        
        <!-- Selector de cantidad -->
        <div class="flex items-center border border-gray-300 rounded-lg">
            <button type="button" onclick="decreaseCartQty({{ $product->Id_Producto }})" 
                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-l-lg">
                -
            </button>
            <input type="number" name="quantity" min="1" max="{{ $product->Cantidad }}" 
                   value="1" id="cart-qty-{{ $product->Id_Producto }}"
                   class="w-12 text-center border-0 focus:ring-0">
            <button type="button" onclick="increaseCartQty({{ $product->Id_Producto }})" 
                    class="w-8 h-8 flex items-center justify-center hover:bg-gray-100 rounded-r-lg">
                +
            </button>
        </div>

        <!-- Botón agregar al carrito -->
        <button type="submit" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium transition-colors duration-200 flex items-center space-x-2"
                id="add-cart-btn-{{ $product->Id_Producto }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
            </svg>
            <span>Agregar</span>
        </button>
    </form>

    <!-- Mensaje de stock -->
    @if($product->Cantidad <= 5 && $product->Cantidad > 0)
        <p class="text-orange-600 text-sm mt-2">
            ⚠️ Solo quedan {{ $product->Cantidad }} unidades
        </p>
    @elseif($product->Cantidad == 0)
        <p class="text-red-600 text-sm mt-2">
            ❌ Producto agotado
        </p>
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

// Manejar envío del formulario usando el sistema unificado
document.getElementById('add-to-cart-form-{{ $product->Id_Producto }}').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const form = this;
    const button = document.getElementById('add-cart-btn-{{ $product->Id_Producto }}');
    const quantity = document.getElementById('cart-qty-{{ $product->Id_Producto }}').value;
    
    // Usar el sistema unificado de carrito
    window.cartManager.addToCart({{ $product->Id_Producto }}, quantity, button).then(success => {
        if (success) {
            // Resetear cantidad a 1
            document.getElementById(`cart-qty-{{ $product->Id_Producto }}`).value = 1;
        }
    });
});

function showNotification(message, type) {
    // Crear notificación
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white font-medium ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// La función updateCartCount ahora está en cart.js
</script>

