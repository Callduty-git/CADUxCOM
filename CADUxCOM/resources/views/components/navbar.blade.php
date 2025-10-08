<nav class="category-bar">
  <!-- Botón menú hamburguesa para móviles -->
  <button class="mobile-menu-toggle" id="mobileMenuToggle">
    <img src="{{ asset('images/menu-icon.png') }}" alt="Menú">
    <span>Categorías</span>
  </button>

  <!-- Lista de categorías con dropdowns -->
  <ul class="categories" id="categoriesList">
    @php
      // Mapeo de iconos para las nuevas categorías
      $iconos = [
        'Despensa' => 'despensa.png',
        'Snacks y Dulces' => 'snacks.png',
        'Bebidas' => 'bebidas.png',
        'Lácteos y Derivados' => 'icon-lacteos.png',
        'Congelados' => 'icon-congelados.png',
        'Panadería' => 'panaderia.png',
        'Cuidado Personal' => 'cuidado-personal.png',
      ];
    @endphp

    @foreach($categorias as $categoria)
      @php
        $icono = $iconos[$categoria->Nombre] ?? 'icon-default.png';
        $subcategoriasCategoria = $subcategorias->where('Id_Categoria', $categoria->Id_Categoria);
      @endphp
      <li class="category-item" data-category-id="{{ $categoria->Id_Categoria }}">
        <div class="category-main">
          <img src="{{ asset('images/' . $icono) }}" alt="{{ $categoria->Nombre }}" class="category-icon">
          <span class="category-name">{{ $categoria->Nombre }}</span>
          @if($subcategoriasCategoria->count() > 0)
            <span class="dropdown-arrow">▼</span>
          @endif
        </div>
        
        @if($subcategoriasCategoria->count() > 0)
          <div class="submenu-dropdown" id="submenu-{{ $categoria->Id_Categoria }}">
            @foreach($subcategoriasCategoria as $subcategoria)
              <a href="{{ route('productos.by-subcategory', $subcategoria->Id_Subcategoria) }}" class="subcategory-link">
                <span class="subcategory-icon">{{ $subcategoria->Icono }}</span>
                <span class="subcategory-name">{{ $subcategoria->Nombre }}</span>
              </a>
            @endforeach
          </div>
        @endif
      </li>
    @endforeach
    
    <!-- Enlace al mapa de ofertas -->
    <li class="nav-item map-link">
      <a class="nav-link map-link-item {{ request()->routeIs('mapa') ? 'active' : '' }}" href="{{ route('mapa') }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        <span>Mapa de Ofertas</span>
      </a>
    </li>
    
    <!-- Enlace a educación -->
    <li class="nav-item education-link">
      <a href="{{ route('education.index') }}" class="nav-link education-link-item">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        <span>Educación</span>
      </a>
    </li>
  </ul>
</nav>

<!-- Script para manejar dropdowns y menú móvil -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const mobileMenuToggle = document.getElementById('mobileMenuToggle');
  const categoriesList = document.getElementById('categoriesList');
  const categoryItems = document.querySelectorAll('.category-item');

  // Toggle menú móvil
  if (mobileMenuToggle) {
    mobileMenuToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      categoriesList.classList.toggle('mobile-open');
      mobileMenuToggle.classList.toggle('active');
    });
  }

  // Manejar dropdowns de categorías
  categoryItems.forEach(function(item) {
    const categoryMain = item.querySelector('.category-main');
    const submenu = item.querySelector('.submenu-dropdown');
    
    if (categoryMain && submenu) {
      categoryMain.addEventListener('click', function(e) {
        e.stopPropagation();
        
        // Cerrar otros dropdowns
        categoryItems.forEach(function(otherItem) {
          if (otherItem !== item) {
            const otherSubmenu = otherItem.querySelector('.submenu-dropdown');
            if (otherSubmenu) {
              otherSubmenu.classList.remove('active');
              otherItem.classList.remove('active');
            }
          }
        });
        
        // Toggle dropdown actual
        submenu.classList.toggle('active');
        item.classList.toggle('active');
      });
    }
  });

  // Cerrar dropdowns al hacer clic fuera
  document.addEventListener('click', function(e) {
    if (!e.target.closest('.category-item')) {
      categoryItems.forEach(function(item) {
        const submenu = item.querySelector('.submenu-dropdown');
        if (submenu) {
          submenu.classList.remove('active');
          item.classList.remove('active');
        }
      });
      categoriesList.classList.remove('mobile-open');
      mobileMenuToggle.classList.remove('active');
    }
  });

  // Manejar resize de ventana
  window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
      categoriesList.classList.remove('mobile-open');
      mobileMenuToggle.classList.remove('active');
    }
  });
});
</script>
