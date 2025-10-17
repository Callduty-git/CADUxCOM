<header class="main-header">
    <div class="left-section">
        <a href="{{ Auth::guard('empresa')->check() ? route('empresa.dashboard') : url('/') }}" class="logo-link">
            <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
            <span class="logo-text">CADUxCOM</span>
        </a>
    </div>

    <div class="right-section">
        <a href="{{ Auth::guard('empresa')->check() ? route('empresa.dashboard') : url('/') }}" aria-label="Ir al inicio">
            <img src="{{ asset('images/casa.png') }}" alt="Inicio" class="header-icon">
        </a>
        
        <!-- Contador del carrito - Solo visible para usuarios normales -->
        @if(!Auth::guard('empresa')->check())
            <x-cart-counter />
        @endif
    </div>
</header>

