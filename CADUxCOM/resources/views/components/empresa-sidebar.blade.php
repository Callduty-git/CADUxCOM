<div class="sidebar-container">
    <aside class="sidebar" id="sidebar">
        <nav class="nav-buttons">
            <a href="{{ route('empresa.dashboard') }}" class="btn {{ request()->routeIs('empresa.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
            <a href="{{ route('empresa.productos.index') }}" class="btn {{ request()->routeIs('empresa.productos.*') ? 'active' : '' }}">
                <i class="fas fa-box"></i>
                <span>Productos</span>
            </a>
            <a href="{{ route('empresa.facturas') }}" class="btn {{ request()->routeIs('empresa.facturas') ? 'active' : '' }}">
                <i class="fas fa-list-alt"></i>
                <span>Log de Productos</span>
            </a>
            <form method="POST" action="{{ route('empresa.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn" aria-label="Cerrar sesión">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Salir</span>
                </button>
            </form>
        </nav>
    </aside>
</div>