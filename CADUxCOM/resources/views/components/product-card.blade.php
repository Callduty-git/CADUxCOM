@props(['product', 'showWishlist' => true, 'showCart' => true])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    <!-- Imagen del producto -->
    <div class="relative">
        <a href="{{ route('productos.show', $product->Id_Producto) }}">
            <img src="{{ $product->Foto ? asset('storage/' . $product->Foto) : asset('images/default-product.png') }}" 
                 alt="{{ $product->Nombre }}" 
                 class="w-full h-48 object-cover">
        </a>
        
        <!-- Badge de descuento -->
        @if($product->PrecioOriginal > $product->Precio)
            <div class="absolute top-2 left-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    -{{ number_format((($product->PrecioOriginal - $product->Precio) / $product->PrecioOriginal) * 100, 0) }}%
                </span>
            </div>
        @endif
        
        <!-- Botón de wishlist -->
        @if($showWishlist)
            <button onclick="toggleWishlist({{ $product->Id_Producto }})" 
                    class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-red-50 transition-colors"
                    id="wishlist-btn-{{ $product->Id_Producto }}">
                <svg class="w-4 h-4 text-gray-500 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        @endif
    </div>
    
    <!-- Información del producto -->
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
            <a href="{{ route('productos.show', $product->Id_Producto) }}" class="hover:text-blue-600 transition-colors">
                {{ $product->Nombre }}
            </a>
        </h3>
        
        <p class="text-sm text-gray-600 mb-2">
            {{ $product->empresa->Nombre }}
        </p>
        
        <p class="text-sm text-gray-500 mb-3">
            Código: {{ $product->Codigo }}
        </p>
        
        <!-- Precio -->
        <div class="mb-3">
            @if($product->PrecioOriginal > $product->Precio)
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-gray-900">
                        ${{ number_format($product->Precio, 0, ',', '.') }}
                    </span>
                    <span class="text-sm text-gray-500 line-through">
                        ${{ number_format($product->PrecioOriginal, 0, ',', '.') }}
                    </span>
                </div>
            @else
                <span class="text-lg font-bold text-gray-900">
                    ${{ number_format($product->Precio, 0, ',', '.') }}
                </span>
            @endif
        </div>
        
        <!-- Stock -->
        <div class="mb-3">
            @if($product->Cantidad > 10)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    En Stock
                </span>
            @elseif($product->Cantidad > 0)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    Poco Stock ({{ $product->Cantidad }})
                </span>
            @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    Agotado
                </span>
            @endif
        </div>
        
        <!-- Botones de acción -->
        <div class="flex space-x-2">
            @if($showCart && $product->Cantidad > 0)
                <button onclick="addToCart({{ $product->Id_Producto }})" 
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m6-5v6a2 2 0 01-2 2H9a2 2 0 01-2-2v-6m8 0V9a2 2 0 00-2-2H9a2 2 0 00-2 2v4.01"></path>
                    </svg>
                    Agregar
                </button>
            @elseif($showCart)
                <button disabled 
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Agotado
                </button>
            @endif
            
            <a href="{{ route('productos.show', $product->Id_Producto) }}" 
               class="inline-flex items-center justify-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Producto agregado al carrito', 'success');
            updateCartCount();
        } else {
            showNotification(data.error || 'Error al agregar al carrito', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al agregar al carrito', 'error');
    });
}

function toggleWishlist(productId) {
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Producto agregado a tu lista de deseos', 'success');
            updateWishlistCount();
        } else {
            showNotification(data.error || 'Error al agregar a la lista de deseos', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error al agregar a la lista de deseos', 'error');
    });
}

function updateCartCount() {
    fetch('{{ route("cart.count") }}')
    .then(response => response.json())
    .then(data => {
        const cartCount = document.getElementById('cart-count');
        if (cartCount) {
            cartCount.textContent = data.count;
        }
    });
}

function updateWishlistCount() {
    fetch('{{ route("wishlist.count") }}')
    .then(response => response.json())
    .then(data => {
        const wishlistCount = document.getElementById('wishlist-count');
        if (wishlistCount) {
            wishlistCount.textContent = data.count;
        }
    });
}

// La función showNotification ahora usa el sistema elegante de notificaciones
// Se carga desde notifications.js
</script>

