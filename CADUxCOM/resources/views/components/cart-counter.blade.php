@php
    $cart = session('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));
    $displayCount = $cartCount > 99 ? '99+' : $cartCount;
@endphp

<div class="dropdown">
    <a href="{{ route('cart.index') }}" class="cart-icon-link">
        <div class="cart-icon-container">
            <img src="{{ asset('images/icon-cart.png') }}" alt="Carrito" class="cart-icon">
            
            @if($cartCount > 0)
                <span class="cart-badge" id="cart-count" data-count="{{ $cartCount }}">
                    <span class="cart-badge-number">{{ $displayCount }}</span>
                </span>
            @endif
        </div>
    </a>
</div>

