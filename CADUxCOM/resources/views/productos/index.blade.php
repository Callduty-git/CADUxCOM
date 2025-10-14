<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header-empresa.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/productos-index.css') }}">
</head>
<body>
    <!-- HEADER DE EMPRESA -->
    <x-header-empresa />

    <div class="main-container">
        <aside class="sidebar">
            <nav class="nav-buttons">
                <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
                <a href="{{ route('empresa.productos.index') }}" class="btn active">Productos</a>
                <a href="{{ route('empresa.facturas') }}" class="btn">Facturas</a>
                <form method="POST" action="{{ route('empresa.logout') }}" class="mt-10">
                    @csrf
                    <button type="submit" class="btn" aria-label="Cerrar sesión">Salir</button>
                </form>
            </nav>
        </aside>
        <main class="dashboard-panel">
            <div class="header-productos">
                <h2>PRODUCTOS</h2>
                <div class="header-center">
                    <form action="{{ route('empresa.productos.index') }}" method="GET" class="search-form">
                        <input type="text" name="query" placeholder="Buscar por nombre o marca..." class="search-input" value="{{ request('query') }}">
                        <button type="submit" class="search-button">🔍</button>
                    </form>
                </div>
                <div class="header-right">
                    <a href="{{ route('productos.create') }}" class="btn btn-crear">+ Crear Producto</a>
                </div>
            </div>

            <section class="productos-lista">
                @forelse ($productos as $producto)
                    <div class="producto-card">
                        @php
                            $imagenRuta = $producto->Foto ? asset('storage/' . $producto->Foto) : asset('images/profile.png');
                        @endphp
                        <img src="{{ $imagenRuta }}" alt="{{ $producto->Nombre }}" class="imagen-producto">

                        <div class="producto-info">
                            <strong>{{ $producto->Nombre }}</strong><br>
                            <span>Marca: {{ $producto->Marca }}</span><br>
                            <span>Caduca: {{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}</span><br>
                            @php
                                $fechaCaducidad = \Carbon\Carbon::parse($producto->Fecha_Caducidad);
                                $hoy = \Carbon\Carbon::now();
                                $estaDisponible = $fechaCaducidad->isFuture() && $producto->Cantidad > 0;
                            @endphp
                            <span class="fw-bold {{ $estaDisponible ? 'text-success' : 'text-danger' }}">
                                {{ $estaDisponible ? '✓ DISPONIBLE' : '✗ NO DISPONIBLE' }}
                            </span>
                        </div>

                        <div class="product-actions">
                            <a href="{{ route('empresa.productos.show', $producto->Id_Producto) }}" class="btn btn-ver">Ver</a>
                            <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="btn btn-editar">Editar</a>
                            <form method="POST" action="{{ route('productos.destroy', $producto->Id_Producto) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-borrar" onclick="return confirm('¿Deseas eliminar este producto?')">Borrar</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p>No hay productos registrados.</p>
                @endforelse
            </section>
        </main>
    </div>

    <script>
        // Cargar estado de vista al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            const savedView = localStorage.getItem('productView') || 'grid';
            const productosLista = document.querySelector('.productos-lista');
            if (savedView === 'list' && productosLista) {
                productosLista.classList.add('list-view');
            }
        });

        function toggleFiltros() {
            const dropdown = document.getElementById('filtrosDropdown');
            dropdown.classList.toggle('show');
        }

        function applyFilters() {
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("empresa.productos.index") }}';

            // Obtener todos los valores de los filtros
            const categoria = document.querySelector('select[name="categoria"]').value;
            const fechaDesde = document.querySelector('input[name="fecha_desde"]').value;
            const fechaHasta = document.querySelector('input[name="fecha_hasta"]').value;
            const precioMin = document.querySelector('input[name="precio_min"]').value;
            const precioMax = document.querySelector('input[name="precio_max"]').value;
            const disponibilidad = document.querySelector('select[name="disponibilidad"]').value;
            const query = document.querySelector('input[name="query"]').value;

            // Agregar parámetros al formulario
            if (categoria) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'categoria';
                input.value = categoria;
                form.appendChild(input);
            }

            if (fechaDesde) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'fecha_desde';
                input.value = fechaDesde;
                form.appendChild(input);
            }

            if (fechaHasta) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'fecha_hasta';
                input.value = fechaHasta;
                form.appendChild(input);
            }

            if (precioMin) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'precio_min';
                input.value = precioMin;
                form.appendChild(input);
            }

            if (precioMax) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'precio_max';
                input.value = precioMax;
                form.appendChild(input);
            }

            if (disponibilidad) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'disponibilidad';
                input.value = disponibilidad;
                form.appendChild(input);
            }

            if (query) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'query';
                input.value = query;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
        }

        function clearFilters() {
            // Limpiar todos los campos de filtro
            document.querySelector('select[name="categoria"]').value = '';
            document.querySelector('input[name="fecha_desde"]').value = '';
            document.querySelector('input[name="fecha_hasta"]').value = '';
            document.querySelector('input[name="precio_min"]').value = '';
            document.querySelector('input[name="precio_max"]').value = '';
            document.querySelector('select[name="disponibilidad"]').value = '';
            document.querySelector('input[name="query"]').value = '';

            // Redirigir sin filtros
            window.location.href = '{{ route("empresa.productos.index") }}';
        }

        // Cerrar dropdown al hacer clic fuera
        document.addEventListener('click', function(event) {
            const filtrosContainer = document.querySelector('.filtros-container');
            const dropdown = document.getElementById('filtrosDropdown');
            
            if (!filtrosContainer.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        // Función para limpiar filtros (compatible con el header)
        function clearFilters() {
            // Limpiar todos los campos del formulario de filtros
            const filterForm = document.getElementById('filterForm');
            if (filterForm) {
                filterForm.reset();
            }
            
            // Limpiar también el campo de búsqueda
            const searchInput = document.querySelector('input[name="query"]');
            if (searchInput) {
                searchInput.value = '';
            }
            
            // Redirigir sin filtros
            window.location.href = '{{ route("empresa.productos.index") }}';
        }
        
    </script>
        </main>
    </div>
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>