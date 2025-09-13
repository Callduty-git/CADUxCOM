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
