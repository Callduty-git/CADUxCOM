@php
    $cart = session('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));
    $displayCount = $cartCount > 99 ? '99+' : $cartCount;
@endphp

<a href="{{ route('cart.index') }}" class="cart-link" title="Ver carrito de compras">
    <img src="{{ asset('images/carrito-de-compras.png') }}" alt="Carrito de compras" class="cart-icon">
    
    @if($cartCount > 0)
        <span class="cart-badge">
            {{ $displayCount }}
        </span>
    @endif
</a>
