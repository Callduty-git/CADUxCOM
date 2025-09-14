<header class="main-header">
    <div class="left-section">
        <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
        <span class="logo-text">CADUxCOM</span>
    </div>

    <div class="search-bar">
        <input type="text" placeholder="Buscar..." class="search-input">
        <img src="{{ asset('images/icon-search.png') }}" alt="Buscar" class="search-icon-inside">
    </div>

    <div class="right-section">
        <div class="dropdown">
            @auth
                <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="header-icon" id="userIcon">

                <div class="dropdown-menu" id="userMenu">
                    @if(Auth::guard('empresa')->check())
                        <a href="{{ route('empresa.dashboard') }}">Perfil empresa</a>
                    @else
                        <a href="{{ route('profile.edit') }}">Perfil usuario</a>
                    @endif

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="all:unset;cursor:pointer;display:block;padding:12px;width:100%;text-align:left;">
                            Cerrar sesión
                        </button>
                    </form>
                </div>
            @else
                <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="header-icon" id="userIcon">
                <div class="dropdown-menu" id="userMenu">
                    <a href="{{ route('login') }}">Login</a>
                    <a href="{{ route('register') }}">Register</a>
                </div>
            @endauth
        </div>

        <img src="{{ asset('images/icon-help.png') }}" alt="Ayuda" class="header-icon">
        <img src="{{ asset('images/icon-cart.png') }}" alt="Carrito" class="header-icon">
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.getElementById('userIcon');
    const userMenu = document.getElementById('userMenu');

    if(userIcon) {
        userIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function(event) {
            if (!userMenu.contains(event.target) && !userIcon.contains(event.target)) {
                userMenu.classList.remove('show');
            }
        });
    }
});
</script>
