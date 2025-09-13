@php
    $cart = session('cart', []);
    $cartCount = array_sum(array_column($cart, 'quantity'));
@endphp

<div class="dropdown">
    <a href="{{ route('cart.index') }}" class="relative">
        <img src="{{ asset('images/icon-cart.png') }}" alt="Carrito" class="header-icon">
        
        <span class="cart-count" id="cart-count" style="background: linear-gradient(135deg, #AA5FC7 0%, #8B5CF6 100%); color: white; font-size: 0.8rem; font-weight: 800; min-width: 24px; height: 24px; border-radius: 50%; display: {{ $cartCount > 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center; border: 3px solid #90D575; box-shadow: 0 4px 15px rgba(170, 95, 199, 0.4), 0 2px 8px rgba(0, 0, 0, 0.2); position: absolute; top: -10px; right: -10px; z-index: 10; font-family: Arial, sans-serif; text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);">
            {{ $cartCount }}
        </span>
    </a>
</div>

