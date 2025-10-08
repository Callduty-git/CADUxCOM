@php
    use App\Models\Categoria;
    use App\Models\Subcategoria;
    
    // Obtener categorías y subcategorías desde la base de datos
    $categorias = Categoria::all();
    $subcategorias = Subcategoria::all();
@endphp

<nav class="navbar-container">
    <div class="navbar-content">
        <!-- Lista de categorías principales -->
        <ul class="categories-list">
            @php
                // Mapeo de iconos para las categorías
                $categoryIcons = [
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
                    $iconName = $categoryIcons[$categoria->Nombre] ?? 'icon-default.png';
                    $subcategoriasCategoria = $subcategorias->where('Id_Categoria', $categoria->Id_Categoria);
                @endphp
                
                <li class="category-item" data-category-id="{{ $categoria->Id_Categoria }}">
                    <button class="category-link" data-category="{{ $categoria->Id_Categoria }}">
                        <img src="{{ asset('images/' . $iconName) }}" alt="{{ $categoria->Nombre }}" class="category-icon">
                        <span class="category-name">{{ $categoria->Nombre }}</span>
                        @if($subcategoriasCategoria->count() > 0)
                            <span class="dropdown-arrow">▼</span>
                        @endif
                    </button>
                    
                    @if($subcategoriasCategoria->count() > 0)
                        <div class="subcategories-dropdown" id="dropdown-{{ $categoria->Id_Categoria }}">
                            <div class="dropdown-content">
                                @foreach($subcategoriasCategoria as $subcategoria)
                                    <a href="{{ route('productos.by-subcategory', $subcategoria->Id_Subcategoria) }}" class="subcategory-link">
                                        <span class="subcategory-icon">📦</span>
                                        <span class="subcategory-name">{{ $subcategoria->Nombre }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
        
        <!-- Enlaces adicionales -->
        <div class="additional-links">
            <a href="{{ route('mapa') }}" class="additional-link">
                <svg class="link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
                <span>Mapa de Ofertas</span>
            </a>
            
            <a href="{{ route('education.index') }}" class="additional-link">
                <svg class="link-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                </svg>
                <span>Educación</span>
            </a>
        </div>
    </div>
</nav>

<!-- Script para manejar dropdowns -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const categoryItems = document.querySelectorAll('.category-item');
    const categoryLinks = document.querySelectorAll('.category-link');
    
    // Manejar clic en categorías
    categoryLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const categoryId = this.getAttribute('data-category');
            const dropdown = document.getElementById('dropdown-' + categoryId);
            
            if (dropdown) {
                // Cerrar otros dropdowns
                categoryItems.forEach(function(item) {
                    if (item.getAttribute('data-category-id') !== categoryId) {
                        const otherDropdown = item.querySelector('.subcategories-dropdown');
                        if (otherDropdown) {
                            otherDropdown.classList.remove('active');
                            item.classList.remove('active');
                        }
                    }
                });
                
                // Toggle dropdown actual
                dropdown.classList.toggle('active');
                this.parentElement.classList.toggle('active');
            }
        });
    });
    
    // Cerrar dropdowns al hacer clic fuera
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.category-item')) {
            categoryItems.forEach(function(item) {
                const dropdown = item.querySelector('.subcategories-dropdown');
                if (dropdown) {
                    dropdown.classList.remove('active');
                    item.classList.remove('active');
                }
            });
        }
    });
});
</script>