<header class="main-header">
    <div class="left-section">
        <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
        <span class="logo-text">CADUxCOM</span>
    </div>


    <div class="right-section">
        <div class="dropdown">
            <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="header-icon" id="cuUserIcon">

            <div class="dropdown-menu" id="cuUserMenu">
                <!-- Info de empresa -->
                <div class="dropdown-user-info">
                    @if(Auth::guard('empresa')->check() && Auth::guard('empresa')->user()->Foto)
                        <img src="{{ asset('storage/' . Auth::guard('empresa')->user()->Foto) }}" alt="Empresa" class="dropdown-company-icon">
                    @else
                        <img src="{{ asset('images/icon-company.png') }}" alt="Empresa" class="dropdown-company-icon">
                    @endif
                    <p class="dropdown-company-name">Empresa {{ Auth::guard('empresa')->check() ? Auth::guard('empresa')->user()->Nombre : 'No autenticada' }}</p>
                </div>

                <!-- Opciones -->
                <div class="dropdown-options">
                    <a href="#" class="dropdown-menu-link">Ayuda</a>
                    <a href="#" class="dropdown-menu-link">Comentar</a>

                    <!-- Eliminar cuenta -->
                    <form method="POST" action="{{ route('empresa.eliminar') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-menu-button danger" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta?')">
                            Eliminar cuenta
                        </button>
                    </form>

                    <!-- Cerrar sesión -->
                    <form method="POST" action="{{ route('empresa.logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-menu-button">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.getElementById('cuUserIcon');
    const userMenu = document.getElementById('cuUserMenu');

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
