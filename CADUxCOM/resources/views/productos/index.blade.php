<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .main-content {
            flex: 1;
        }
        
        .header {
            border-bottom: 3px solid #006400;
        }
        
        /* ====== SIDEBAR CONTAINER ====== */
        .sidebar-container {
            position: fixed;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 450px;
            height: 80vh;
            z-index: 1000;
            transition: all 0.3s ease;
        }
        
        .sidebar {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1001;
            transition: all 0.3s ease;
            opacity: 0.95;
        }
        
        .sidebar:hover {
            opacity: 1;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            transform: scale(1.02);
        }
        
        .sidebar-container:hover {
            transform: translateY(-50%) scale(1.02);
        }
        
        .dashboard-panel {
            width: 100%;
            max-width: 1200px; /* Mantener el tamaño original */
            margin: 0 auto; /* Centrar el panel */
        }

        /* ====== MODAL ESTILOS ====== */
        .modal { display: none; position: fixed; z-index: 2000; padding-top: 60px; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);}
        .modal-content { background-color: #fff; margin: auto; padding: 20px; border-radius: 10px; width: 500px; max-width: 90%; box-shadow: 0px 4px 8px rgba(0,0,0,0.3);}
        .close { color: #aaa; float: right; font-size: 24px; font-weight: bold; cursor: pointer;}
        .modal-content label { display: block; margin-top: 10px; font-weight: bold;}
        .modal-content input { width: 100%; padding: 8px; margin-top: 4px; border-radius: 6px; border: 1px solid #ccc;}
        .save-btn { margin-top: 15px; padding: 10px 15px; background-color: purple; color: white; border: none; border-radius: 6px; cursor: pointer;}

        /* ====== ESTILOS MODAL BIENVENIDA ====== */
        .modal-bienvenida {
            display: none;
            position: fixed;
            z-index: 3000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.6);
            justify-content: center;
            align-items: center;
        }

        .modal-visible {
            display: flex;
        }

        .modal-contenido-bienvenida {
            background-color: #333;
            color: #fff;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            width: 400px;
            max-width: 90%;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.5);
            border: 2px solid #8B4513;
            position: relative;
            font-family: 'Inter', sans-serif;
        }

        .header-modal-bienvenida .logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .title-modal {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .body-modal-bienvenida h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 10px 0;
        }

        .body-modal-bienvenida p {
            font-size: 1rem;
            font-weight: 400;
        }

        /* ====== ESTILOS DE FILTROS ====== */
        .filtros-container {
            position: relative;
            display: inline-block;
        }

        .btn-filtros {
            background-color: #D994F4;
            color: white;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 8px 16px;
            font-weight: bold;
            cursor: pointer;
            margin-right: 10px;
            transition: background 0.3s ease;
        }

        .btn-filtros:hover {
            background-color: #d88ef0;
        }

        .filtros-dropdown {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 20px;
            min-width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            z-index: 1000;
            margin-top: 5px;
        }

        .filtros-dropdown.show {
            display: block;
        }

        .filtro-item {
            margin-bottom: 15px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filtro-item span {
            font-weight: bold;
            color: #333;
            font-size: 14px;
        }

        .filtro-item select,
        .filtro-item input {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        .fecha-range,
        .precio-range {
            display: flex;
            gap: 10px;
        }

        .fecha-range input,
        .precio-range input {
            flex: 1;
        }

        .filtro-actions {
            margin-top: 15px;
            text-align: center;
        }

        .btn-limpiar {
            background-color: #C75F5F;
            color: white;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 8px 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-limpiar:hover {
            background-color: #c9302c;
        }

        /* ====== ESTILOS DEL HEADER DE PRODUCTOS ====== */
        .header-productos {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 15px;
            border: 2px solid #e9ecef;
        }

        .header-productos h2 {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }

        .header-center {
            flex: 1;
            display: flex;
            justify-content: center;
            margin: 0 20px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        /* ====== ESTILOS DEL PANEL DE FILTROS ====== */
        .filter-panel {
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 2px solid #000;
            border-radius: 15px;
            padding: 25px;
            min-width: 350px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            z-index: 1000;
            margin-top: 10px;
        }

        .filter-panel.hidden {
            display: none;
        }

        .filter-panel h3 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin: 0 0 20px 0;
            text-align: center;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 10px;
        }

        .filter-group {
            margin-bottom: 20px;
        }

        .filter-toggle {
            background: none;
            border: none;
            font-size: 16px;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            padding: 10px 0;
            width: 100%;
            text-align: left;
            border-bottom: 1px solid #e9ecef;
            transition: color 0.3s ease;
        }

        .filter-toggle:hover {
            color: #D994F4;
        }

        .filter-options {
            margin-top: 10px;
            padding-left: 20px;
        }

        .filter-options.hidden {
            display: none;
        }

        .filter-options li {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-options input[type="checkbox"],
        .filter-options input[type="radio"] {
            width: 16px;
            height: 16px;
            accent-color: #D994F4;
        }

        .filter-options input[type="date"],
        .filter-options input[type="number"] {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
            width: 100%;
            max-width: 150px;
        }

        .filter-options label {
            font-weight: bold;
            color: #555;
            min-width: 60px;
        }

        .filter-actions {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .btn-aplicar {
            background-color: #D994F4;
            color: white;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-aplicar:hover {
            background-color: #d88ef0;
        }

        .btn-limpiar {
            background-color: #C75F5F;
            color: white;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-limpiar:hover {
            background-color: #c9302c;
        }

        /* ====== ESTILOS PARA VISTA DE LISTA ====== */
        .productos-lista.list-view {
            display: flex;
            flex-direction: column;
            gap: 15px;
            padding: 20px 0;
        }

        .productos-lista.list-view .producto-card {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 25px;
            margin-bottom: 0;
            width: 100%;
            max-width: none;
            height: auto;
            min-height: 120px;
            background-color: #f9f9f9;
            border: 2px solid #000000;
            border-radius: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .productos-lista.list-view .producto-card:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
            border-color: #D994F4;
            background-color: #ffffff;
        }

        .productos-lista.list-view .imagen-producto {
            width: 130px;
            height: 130px;
            margin-right: 30px;
            margin-bottom: 0;
            flex-shrink: 0;
            border-radius: 10px;
            border: 2px solid #e9ecef;
            object-fit: cover;
        }

        .productos-lista.list-view .producto-info {
            flex: 1;
            margin-bottom: 0;
            text-align: left;
        }

        .productos-lista.list-view .producto-info strong {
            font-size: 24px;
            font-weight: 700;
            display: block;
            margin-bottom: 8px;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .productos-lista.list-view .producto-info span {
            font-size: 18px;
            display: block;
            margin-bottom: 6px;
            color: #555;
            font-weight: 500;
        }

        .productos-lista.list-view .producto-info span:first-of-type {
            color: #D994F4;
            font-weight: 600;
        }

        .productos-lista.list-view .product-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-left: 30px;
            min-width: 140px;
        }

        .productos-lista.list-view .product-actions .btn {
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            min-width: 120px;
            border-radius: 8px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .productos-lista.list-view .product-actions .btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        }

        .productos-lista.list-view .product-actions .btn-ver {
            background-color: #28a745;
            color: white;
            border: 2px solid #000000;
        }

        .productos-lista.list-view .product-actions .btn-ver:hover {
            background-color: #ffffff;
            color: #28a745;
            border-color: #000000;
        }

        .productos-lista.list-view .product-actions .btn-editar {
            background-color: #D994F4;
            color: white;
            border: 2px solid #000000;
        }

        .productos-lista.list-view .product-actions .btn-editar:hover {
            background-color: #AA5FC7;
            border-color: #000000;
        }

        .productos-lista.list-view .product-actions .btn-borrar {
            background-color: #dc3545;
            color: white;
            border: 2px solid #000000;
        }

        .productos-lista.list-view .product-actions .btn-borrar:hover {
            background-color: #c9302c;
            border-color: #000000;
        }
    </style>
</head>
<body>
    <div class="main-content">
        <x-header-productos :categorias="$categorias" :subcategorias="$subcategorias" />

    <div class="sidebar-container">
        <aside class="sidebar" id="sidebar">
            <nav class="nav-buttons">
                <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
                <a href="{{ route('empresa.productos.index') }}" class="btn">Productos</a>
                <a href="{{ route('empresa.facturas') }}" class="btn">Log de Productos</a>
                <form method="POST" action="{{ route('empresa.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn">Salir</button>
                </form>
            </nav>
        </aside>
    </div>

    <div class="main-container">
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
                            <span style="color: {{ $estaDisponible ? '#28a745' : '#dc3545' }}; font-weight: bold;">
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
        
        // Funcionalidad del sidebar deslizable
        const sidebar = document.getElementById('sidebar');
        let sidebarTimeout;
        
        // Mostrar sidebar al hacer hover en el área izquierda
        document.addEventListener('mousemove', function(e) {
            if (e.clientX <= 20) { // Área de 20px desde el borde izquierdo
                clearTimeout(sidebarTimeout);
                sidebar.style.left = '0';
            }
        });
        
        // Ocultar sidebar cuando el mouse sale del área
        sidebar.addEventListener('mouseleave', function() {
            sidebarTimeout = setTimeout(function() {
                sidebar.style.left = '-250px';
            }, 300); // Delay de 300ms antes de ocultar
        });
        
        // Cancelar ocultar si el mouse vuelve al sidebar
        sidebar.addEventListener('mouseenter', function() {
            clearTimeout(sidebarTimeout);
        });
    </script>
    </div>

    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>