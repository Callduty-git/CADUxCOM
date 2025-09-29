@props(['product', 'showWishlist' => true, 'showCart' => true])

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow">
    {{-- Imagen del producto --}}
    <div class="relative">
        <a href="{{ route('productos.show', $product->Id_Producto) }}">
            <img src="{{ $product->Foto ? asset('storage/' . $product->Foto) : asset('images/default-product.png') }}" 
                 alt="{{ $product->Nombre }}" class="w-full h-48 object-cover">
        </a>

        {{-- Badge de descuento --}}
        @php
            $discountInfo = method_exists($product, 'getDiscountInfo') 
                            ? $product->getDiscountInfo() 
                            : ['has_discount' => $product->PrecioOriginal > $product->Precio,
                               'discount_percentage' => $product->PrecioOriginal > 0 ? round((($product->PrecioOriginal - $product->Precio)/$product->PrecioOriginal)*100) : 0,
                               'discounted_price' => $product->Precio,
                               'original_price' => $product->PrecioOriginal ?? $product->Precio,
                               'expiry_status' => null,
                               'savings_message' => null,
                               'expiry_label' => null];
        @endphp

        @if($discountInfo['has_discount'])
            <div class="absolute top-2 left-2">
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                    @if($discountInfo['expiry_status'] === 'critical') bg-red-100 text-red-800
                    @elseif($discountInfo['expiry_status'] === 'urgent') bg-orange-100 text-orange-800
                    @elseif($discountInfo['expiry_status'] === 'near_expiry') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800 @endif">
                    -{{ $discountInfo['discount_percentage'] }}%
                </span>
            </div>
        @endif

        {{-- Wishlist --}}
        @if($showWishlist && auth()->check())
            <button onclick="toggleWishlist({{ $product->Id_Producto }})" 
                    class="absolute top-2 right-2 w-8 h-8 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-red-50 transition-colors group"
                    id="wishlist-btn-{{ $product->Id_Producto }}">
                <svg class="w-4 h-4 text-gray-500 hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
            </button>
        @endif
    </div>

    {{-- Información --}}
    <div class="p-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">
            <a href="{{ route('productos.show', $product->Id_Producto) }}" class="hover:text-blue-600 transition-colors">
                {{ $product->Nombre }}
            </a>
        </h3>

        <p class="text-sm text-gray-600 mb-2">{{ $product->empresa->Nombre ?? '' }}</p>
        <p class="text-sm text-gray-500 mb-3">Código: {{ $product->Codigo }}</p>

        {{-- Precio --}}
        <div class="mb-3">
            @if($discountInfo['has_discount'])
                <div class="flex items-center space-x-2">
                    <span class="text-lg font-bold text-gray-900">${{ number_format($discountInfo['discounted_price'], 0, ',', '.') }}</span>
                    <span class="text-sm text-gray-500 line-through">${{ number_format($discountInfo['original_price'], 0, ',', '.') }}</span>
                </div>
                @if($discountInfo['savings_message'])
                    <div class="text-xs text-green-600 font-medium">{{ $discountInfo['savings_message'] }}</div>
                @endif
            @else
                <span class="text-lg font-bold text-gray-900">${{ number_format($product->Precio, 0, ',', '.') }}</span>
            @endif
        </div>

        {{-- Stock --}}
        <div class="mb-3">
            @if($product->Cantidad > 10)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">En Stock</span>
            @elseif($product->Cantidad > 0)
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Poco Stock ({{ $product->Cantidad }})</span>
            @else
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Agotado</span>
            @endif
        </div>

        {{-- Fecha de caducidad --}}
        @if($product->Fecha_Caducidad)
            <div class="mb-3 text-xs text-gray-600">Caduca: {{ \Carbon\Carbon::parse($product->Fecha_Caducidad)->format('d/m/Y') }}</div>
        @endif

        {{-- Botones --}}
        <div class="flex space-x-2">
            @if($showCart && $product->Cantidad > 0)
                <button onclick="addToCart({{ $product->Id_Producto }})" 
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors">
                    Agregar
                </button>
            @elseif($showCart)
                <button disabled class="flex-1 inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md text-gray-400 bg-gray-100 cursor-not-allowed">Agotado</button>
            @endif

            <a href="{{ route('productos.show', $product->Id_Producto) }}" 
               class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">Ver</a>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    }).then(r => r.json()).then(data => {
        if(data.success) updateCartCount();
    });
}

function toggleWishlist(productId) {
    fetch('{{ route("wishlist.add") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ product_id: productId })
    }).then(r => r.json()).then(data => {
        if(data.success) updateWishlistCount();
    });
}

function updateCartCount() {
    fetch('{{ route("cart.count") }}').then(r => r.json()).then(data => {
        const el = document.getElementById('cart-count');
        if(el) el.textContent = data.count;
    });
}

function updateWishlistCount() {
    fetch('{{ route("wishlist.count") }}').then(r => r.json()).then(data => {
        const el = document.getElementById('wishlist-count');
        if(el) el.textContent = data.count;
    });
}
</script>
