@props(['product', 'showWishlist' => true, 'showCart' => true])

<div class="product-card">
    {{-- Imagen del producto --}}
    <div class="media-wrap">
        <a href="{{ route('productos.show', $product->Id_Producto) }}">
            <img src="{{ $product->Foto ? asset('storage/' . $product->Foto) : asset('images/default-product.png') }}" 
                 alt="{{ $product->Nombre }}" class="product-image">
        </a>

        {{-- Badge de descuento --}}
        @php
            $discountInfo = method_exists($product, 'getDiscountInfo') 
                            ? $product->getDiscountInfo() 
                            : ['has_discount' => $product->PrecioOriginal > $product->Precio,
                               'discount_percentage' => $product->PrecioOriginal > 0 ? round((($product->PrecioOriginal - $product->Precio)/$product->PrecioOriginal)*100, 0) : 0,
                               'discounted_price' => $product->Precio,
                               'original_price' => $product->PrecioOriginal ?? $product->Precio,
                               'expiry_status' => null,
                               'savings_message' => null,
                               'expiry_label' => null];
        @endphp

        @if($discountInfo['has_discount'])
            <div class="badge-discount">
                -{{ $discountInfo['discount_percentage'] }}%
            </div>
        @endif

        {{-- Wishlist --}}
        @if($showWishlist)
            <x-wishlist-button :product-id="$product->Id_Producto" />
        @endif
    </div>

    {{-- Información del producto --}}
    <div class="product-details">
        <h3 class="product-name">
            <a href="{{ route('productos.show', $product->Id_Producto) }}">
                {{ $product->Nombre }}
            </a>
        </h3>

        <p class="product-brand">{{ $product->Codigo }}</p>
        <p class="product-company">{{ $product->empresa->Nombre ?? '' }}</p>
    </div>

    {{-- Footer morado con precios y acciones --}}
    <div class="product-footer">
        {{-- Precios --}}
        <div class="footer-prices">
            @if($discountInfo['has_discount'])
                <span class="footer-original">${{ number_format($discountInfo['original_price'], 0, ',', '.') }}</span>
                <span class="footer-arrow">→</span>
                <span class="footer-discount">${{ number_format($discountInfo['discounted_price'], 0, ',', '.') }}</span>
            @else
                <span class="footer-discount">${{ number_format($product->Precio, 0, ',', '.') }}</span>
            @endif
        </div>

        {{-- Fecha de caducidad --}}
        @if($product->Fecha_Caducidad)
            <div class="footer-expire">
                Vence: {{ \Carbon\Carbon::parse($product->Fecha_Caducidad)->format('d/m/Y') }}
            </div>
        @endif

        {{-- Botones de acción --}}
        <div class="footer-actions">
            <a href="{{ route('productos.show', $product->Id_Producto) }}" 
               class="btn btn-secondary">Ver detalles</a>
            
            @if($showCart && $product->Cantidad > 0)
                <button data-product-id="{{ $product->Id_Producto }}" 
                        class="add-to-cart-btn btn btn-primary">
                    Agregar
                </button>
            @elseif($showCart)
                <button disabled class="btn btn-secondary" style="opacity: 0.5; cursor: not-allowed;">
                    Agotado
                </button>
            @endif
        </div>
    </div>
</div>

<script>
// Event listeners para los botones
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners para botones de agregar al carrito
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            addToCart(productId);
        });
    });
    
});

async function addToCart(productId) {
    // Usar el sistema unificado del carrito si está disponible
    if (window.cartManager && window.cartManager.addToCart) {
        return await window.cartManager.addToCart(productId, 1);
    }
    
    // Fallback: implementación local (mantener compatibilidad)
    try {
        const response = await fetch('{{ route("cart.add") }}', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': '{{ csrf_token() }}' 
            },
            body: JSON.stringify({ product_id: productId, quantity: 1 })
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateCartCount();
            // Usar el sistema unificado de notificaciones si está disponible
            if (window.cartManager && window.cartManager.showNotification) {
                window.cartManager.showNotification('Producto agregado al carrito', 'success');
            }
        } else {
            if (window.cartManager && window.cartManager.showNotification) {
                window.cartManager.showNotification(data.message || 'Error al agregar el producto', 'error');
            }
        }
    } catch (error) {
        console.error('Error:', error);
        if (window.cartManager && window.cartManager.showNotification) {
            window.cartManager.showNotification('Error al agregar el producto', 'error');
        }
    }
}


function updateCartCount() {
    // Usar el sistema unificado si está disponible
    if (window.cartManager && window.cartManager.updateCartCounter) {
        window.cartManager.updateCartCounter();
        return;
    }
    
    // Fallback: implementación local
    fetch('{{ route("cart.count") }}').then(r => r.json()).then(data => {
        const el = document.getElementById('cart-count');
        if(el) el.textContent = data.count;
    }).catch(error => console.error('Error updating cart count:', error));
}

function updateWishlistCount() {
    // Usar el sistema unificado si está disponible
    if (window.updateWishlistCount) {
        window.updateWishlistCount();
        return;
    }
    
    // Fallback: implementación local
    fetch('{{ route("wishlist.count") }}').then(r => r.json()).then(data => {
        const el = document.getElementById('wishlist-count');
        if(el) el.textContent = data.count;
    }).catch(error => console.error('Error updating wishlist count:', error));
}
</script>
