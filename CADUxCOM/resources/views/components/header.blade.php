<header class="main-header">
    <!-- Sección izquierda: Logo -->
    <div class="left-section">
        <a href="{{ route('home') }}" class="logo-link">
            <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
            <span class="logo-text">CADUxCOM</span>
        </a>
    </div>

    <!-- Sección central: Barra de búsqueda -->
    <div class="center-section">
        <div class="search-bar">
            <input type="text" placeholder="Buscar productos..." class="search-input">
            <button class="search-button" type="button">
                <img src="{{ asset('images/icon-search.png') }}" alt="Buscar" class="search-icon">
            </button>
        </div>
    </div>

    <!-- Sección derecha: Iconos de usuario -->
    <div class="right-section">
        <!-- Carrito de compras -->
        <div class="cart-container">
            <x-cart-counter />
        </div>

        <!-- Favoritos -->
        <a href="{{ route('wishlist.index') }}" class="header-icon-link" title="Mis Favoritos">
            <img src="{{ asset('images/favoritos.png') }}" alt="Favoritos" class="header-icon">
            <span class="wishlist-count is-hidden" id="wishlist-count" data-url="{{ auth()->check() ? route('wishlist.count') : '' }}">0</span>
        </a>

        <!-- Ayuda -->
        <a href="{{ route('help') }}" class="header-icon-link" title="Ayuda">
            <img src="{{ asset('images/icon-help.png') }}" alt="Ayuda" class="header-icon">
        </a>

        <!-- Usuario / Login-Register -->
        <div class="user-section">
            @auth
                <!-- Usuario autenticado -->
                <div class="user-dropdown">
                    <button class="user-button" id="userButton" type="button">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="user-icon">
                        <span class="user-name">{{ Auth::user()->name ?? 'Usuario' }}</span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                    <div class="user-menu" id="userMenu">
                        @if(Auth::guard('empresa')->check())
                            <a href="{{ route('empresa.dashboard') }}" class="menu-item">
                                <span class="menu-icon">🏢</span>
                                Perfil empresa
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}" class="menu-item">
                                <span class="menu-icon">👤</span>
                                Perfil usuario
                            </a>
                        @endif
                        <div class="menu-divider"></div>
                        <form method="POST" action="{{ route('logout') }}" class="logout-form">
                            @csrf
                            <button type="submit" class="menu-item logout-btn">
                                <span class="menu-icon">🚪</span>
                                Cerrar sesión
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <!-- Usuario no autenticado -->
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="auth-btn login-btn">
                        <span class="btn-icon">🔑</span>
                        <span class="btn-text">Login</span>
                    </a>
                    <a href="{{ route('register') }}" class="auth-btn register-btn">
                        <span class="btn-icon">📝</span>
                        <span class="btn-text">Register</span>
                    </a>
                </div>
            @endauth
        </div>
    </div>
</header>

<!-- JavaScript del carrito -->
<script src="{{ asset('js/cart.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // === FUNCIONALIDAD DE WISHLIST ===
        const wishlistUrl = (document.getElementById('wishlist-count')?.dataset.url || null);

        function updateWishlistCountHeader() {
            if (!wishlistUrl) return;

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

        updateWishlistCountHeader();

        // === FUNCIONALIDAD DE DROPDOWN DE USUARIO ===
        const userButton = document.getElementById('userButton');
        const userMenu = document.getElementById('userMenu');

        if (userButton && userMenu) {
            userButton.addEventListener('click', function(e) {
                e.stopPropagation();
                userMenu.classList.toggle('show');
                userButton.classList.toggle('active');
            });

            // Cerrar dropdown al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!userMenu.contains(event.target) && !userButton.contains(event.target)) {
                    userMenu.classList.remove('show');
                    userButton.classList.remove('active');
                }
            });

            // Cerrar dropdown con Escape
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    userMenu.classList.remove('show');
                    userButton.classList.remove('active');
                }
            });
        }

        // === FUNCIONALIDAD DE BÚSQUEDA ===
        const searchInput = document.querySelector('.search-input');
        const searchButton = document.querySelector('.search-button');

        if (searchButton) {
            searchButton.addEventListener('click', function() {
                const query = searchInput.value.trim();
                if (query) {
                    // Aquí puedes implementar la lógica de búsqueda
                    console.log('Buscando:', query);
                    // window.location.href = `/productos?search=${encodeURIComponent(query)}`;
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    const query = this.value.trim();
                    if (query) {
                        // Aquí puedes implementar la lógica de búsqueda
                        console.log('Buscando:', query);
                        // window.location.href = `/productos?search=${encodeURIComponent(query)}`;
                    }
                }
            });
        }

        // === RESPONSIVE HEADER ===
        function handleResize() {
            const header = document.querySelector('.main-header');
            const rightSection = document.querySelector('.right-section');
            
            if (window.innerWidth <= 768) {
                header.classList.add('mobile');
            } else {
                header.classList.remove('mobile');
            }
        }

        window.addEventListener('resize', handleResize);
        handleResize(); // Ejecutar al cargar
    });
</script>
