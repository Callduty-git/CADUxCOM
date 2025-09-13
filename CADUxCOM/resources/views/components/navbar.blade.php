<nav class="category-bar">
  <!-- Botón menú desplegable -->
  <button class="toggle-submenu">
    <img src="{{ asset('images/menu-icon.png') }}" alt="Menú">
    Categorías
  </button>

  <!-- Lista de categorías con íconos -->
  <ul class="categories">
    @php
      $iconos = [
        1 => 'icon-lacteos.png',
        2 => 'icon-granos.png',
        3 => 'icon-harinas.png',
        4 => 'icon-congelados.png',
        5 => 'icon-enlatados.png',
      ];
    @endphp

    @foreach($categorias as $categoria)
      @php
        $icono = $iconos[$categoria->Id_Categoria] ?? 'icon-default.png';
      @endphp
      <li>
        <img src="{{ asset('images/' . $icono) }}" alt="{{ $categoria->Nombre }}">
        {{ $categoria->Nombre }}
      </li>
    @endforeach
    
    <!-- Enlace al mapa de ofertas -->
    <li class="map-link">
      <a href="{{ route('mapa') }}" class="map-link-item">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        Mapa de Ofertas
      </a>
    </li>
    
    <!-- Enlace a educación -->
    <li class="education-link">
      <a href="{{ route('education.index') }}" class="education-link-item">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
        </svg>
        Educación
      </a>
    </li>
  </ul>

  <!-- Subcategorías visibles al desplegar -->
  <div id="submenu" class="submenu hidden">
    @foreach($subcategorias as $sub)
      <span>{{ $sub->Nombre }}</span>
    @endforeach
  </div>

  <!-- Script para mostrar u ocultar el menú y cerrarlo si se hace clic fuera -->
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toggleButton = document.querySelector('.toggle-submenu');
      const submenu = document.getElementById('submenu');

      toggleButton.addEventListener('click', function (e) {
        submenu.classList.toggle('hidden');
        e.stopPropagation();
      });

      document.addEventListener('click', function (e) {
        const isClickInside = submenu.contains(e.target) || toggleButton.contains(e.target);
        if (!isClickInside) {
          submenu.classList.add('hidden');
        }
      });
    });
  </script>
</nav>
