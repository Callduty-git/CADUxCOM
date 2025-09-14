
<header class="main-header">
    <div class="left-section">
        <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
        <span class="logo-text">CADUxCOM</span>
    </div>

    <div class="right-section">
        <div class="dropdown">
            <img src="{{ asset('images/filtrar.png') }}" alt="Filtros" class="header-icon" id="filterBtn">

            <div id="filterPanel" class="filter-panel hidden">
                <h3>Filtros</h3>
                <form id="filterForm" method="GET" action="{{ route('empresa.productos.index') }}">

                <div class="filter-group">
                    <button type="button" class="filter-toggle">1. Categoría de producto ▸</button>
                    <ul class="filter-options hidden">
                        @foreach($categorias ?? [] as $categoria)
                            <li>
                                <input type="checkbox" name="categoria[]" value="{{ $categoria->Id_Categoria }}" 
                                       {{ in_array($categoria->Id_Categoria, (array)request('categoria', [])) ? 'checked' : '' }}>
                                {{ $categoria->Icono }} {{ $categoria->Nombre }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="filter-group">
                    <button type="button" class="filter-toggle">2. Subcategoría ▸</button>
                    <ul class="filter-options hidden">
                        @foreach($subcategorias ?? [] as $subcategoria)
                            <li>
                                <input type="checkbox" name="subcategoria[]" value="{{ $subcategoria->Id_Subcategoria }}" 
                                       {{ in_array($subcategoria->Id_Subcategoria, (array)request('subcategoria', [])) ? 'checked' : '' }}>
                                {{ $subcategoria->Icono }} {{ $subcategoria->Nombre }}
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="filter-group">
                    <button type="button" class="filter-toggle">3. Fecha de vencimiento (rango) ▸</button>
                    <ul class="filter-options hidden">
                        <li>
                            <label>Desde:</label>
                            <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}">
                        </li>
                        <li>
                            <label>Hasta:</label>
                            <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}">
                        </li>
                    </ul>
                </div>

                <div class="filter-group">
                    <button type="button" class="filter-toggle">4. Precio ▸</button>
                    <ul class="filter-options hidden">
                        <li>
                            <label>Mínimo:</label>
                            <input type="number" name="precio_min" value="{{ request('precio_min') }}" step="0.01" placeholder="0">
                        </li>
                        <li>
                            <label>Máximo:</label>
                            <input type="number" name="precio_max" value="{{ request('precio_max') }}" step="0.01" placeholder="999999">
                        </li>
                    </ul>
                </div>

                <div class="filter-group">
                    <button type="button" class="filter-toggle">5. Disponibilidad ▸</button>
                    <ul class="filter-options hidden">
                        <li><input type="radio" name="disponibilidad" value="disponible" {{ request('disponibilidad') == 'disponible' ? 'checked' : '' }}> Disponible</li>
                        <li><input type="radio" name="disponibilidad" value="por_vencer" {{ request('disponibilidad') == 'por_vencer' ? 'checked' : '' }}> Por vencer</li>
                        <li><input type="radio" name="disponibilidad" value="agotado" {{ request('disponibilidad') == 'agotado' ? 'checked' : '' }}> Agotado</li>
                    </ul>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn-aplicar">Aplicar filtros</button>
                    <button type="button" onclick="clearFilters()" class="btn-limpiar">Limpiar</button>
                </div>

                </form>
            </div>
        </div>

        <img src="{{ asset('images/rectangulo-lado.png') }}" alt="Cambiar vista" class="header-icon" id="toggleViewBtn">

        <div class="dropdown">
            <img src="{{ asset('images/icon-user.png') }}" alt="Usuario" class="header-icon" id="cuUserIcon">

            <div class="dropdown-menu" id="cuUserMenu">
                <div class="dropdown-user-info">
                    @if(Auth::guard('empresa')->user()->Foto)
                        <img src="{{ asset('storage/' . Auth::guard('empresa')->user()->Foto) }}" alt="Empresa" class="dropdown-company-icon">
                    @else
                        <img src="{{ asset('images/icon-company.png') }}" alt="Empresa" class="dropdown-company-icon">
                    @endif
                    <p class="dropdown-company-name">Empresa {{ Auth::guard('empresa')->user()->Nombre ?? 'x' }}</p>
                </div>

                <div class="dropdown-options">
                    <a href="#" class="dropdown-menu-link">Ayuda</a>
                    <a href="#" class="dropdown-menu-link">Comentar</a>

                    <form method="POST" action="{{ route('empresa.eliminar') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-menu-button danger" onclick="return confirm('¿Seguro que deseas eliminar tu cuenta?')">
                            Eliminar cuenta
                        </button>
                    </form>

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
document.addEventListener('DOMContentLoaded', function () {
    const filterBtn = document.getElementById('filterBtn');
    const filterPanel = document.getElementById('filterPanel');
    if (filterBtn && filterPanel) {
        filterBtn.addEventListener('click', () => {
            filterPanel.classList.toggle('hidden');
        });
    }

    document.querySelectorAll('.filter-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.filter-options').forEach(opt => {
                if (opt !== btn.nextElementSibling) {
                    opt.classList.add('hidden');
                    opt.previousElementSibling.textContent =
                        opt.previousElementSibling.textContent.replace('▾', '▸');
                }
            });
            const options = btn.nextElementSibling;
            options.classList.toggle('hidden');
            btn.textContent = btn.textContent.includes('▸')
                ? btn.textContent.replace('▸', '▾')
                : btn.textContent.replace('▾', '▸');
        });
    });

    const toggleViewBtn = document.getElementById('toggleViewBtn');
    const productosLista = document.querySelector('.productos-lista');
    if (toggleViewBtn && productosLista) {
        // Cargar estado guardado
        const savedView = localStorage.getItem('productView') || 'grid';
        if (savedView === 'list') {
            productosLista.classList.add('list-view');
            // Cambiar icono a rectangulo-arriba.png si está en vista de lista
            toggleViewBtn.src = '{{ asset("images/rectangulo-arriba.png") }}';
        }
        
        toggleViewBtn.addEventListener('click', () => {
            productosLista.classList.toggle('list-view');
            
            // Cambiar icono según la vista
            const isListView = productosLista.classList.contains('list-view');
            if (isListView) {
                toggleViewBtn.src = '{{ asset("images/rectangulo-arriba.png") }}';
            } else {
                toggleViewBtn.src = '{{ asset("images/rectangulo-lado.png") }}';
            }
            
            // Guardar estado
            localStorage.setItem('productView', isListView ? 'list' : 'grid');
        });
    }

    const userIcon = document.getElementById('cuUserIcon');
    const userMenu = document.getElementById('cuUserMenu');
    if (userIcon) {
        userIcon.addEventListener('click', function (e) {
            e.stopPropagation();
            userMenu.classList.toggle('show');
        });

        document.addEventListener('click', function (event) {
            if (!userMenu.contains(event.target) && !userIcon.contains(event.target)) {
                userMenu.classList.remove('show');
            }
        });
    }
});

function clearFilters() {
    // Limpiar todos los campos del formulario
    const form = document.getElementById('filterForm');
    if (form) {
        form.reset();
    }
    
    // Limpiar también el campo de búsqueda si existe
    const searchInput = document.querySelector('input[name="query"]');
    if (searchInput) {
        searchInput.value = '';
    }
    
    // Redirigir sin filtros
    window.location.href = '{{ route("empresa.productos.index") }}';
}
</script>
