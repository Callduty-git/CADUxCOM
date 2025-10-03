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
                        <button type="submit" class="dropdown-btn">
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

        <a href="{{ route('wishlist.index') }}" class="header-icon-link" title="Mis Favoritos">
            <img src="{{ asset('images/favoritos.png') }}" alt="Favoritos" class="header-icon">
            <span class="wishlist-count is-hidden" id="wishlist-count" data-url="{{ auth()->check() ? route('wishlist.count') : '' }}">0</span>
        </a>

        <img src="{{ asset('images/icon-help.png') }}" alt="Ayuda" class="header-icon">
        <x-cart-counter />
    </div>
</header>

<!-- JavaScript del carrito -->
<script src="{{ asset('js/cart.js') }}"></script>

<script>
    // Leer la URL desde el atributo data para evitar Blade dentro de JS
    const wishlistUrl = (document.getElementById('wishlist-count')?.dataset.url || null);

    document.addEventListener('DOMContentLoaded', function() {
        function updateWishlistCountHeader() {
            if (!wishlistUrl) return; // Si no está logueado no ejecuta nada

            fetch(wishlistUrl)
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('wishlist-count');
                    if (countElement) {
                        if (data.count > 0) {
                            countElement.textContent = data.count;
                            countElement.style.display = 'flex';
                        } else {
                            countElement.style.display = 'none';
                        }
                        countElement.classList.add('update');
                        setTimeout(() => countElement.classList.remove('update'), 500);
                    }
                })
                .catch(error => {
                    console.log('Error al obtener contador de wishlist:', error);
                });
        }

        // Actualizamos el contador al cargar
        updateWishlistCountHeader();

        // Dropdown usuario
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
