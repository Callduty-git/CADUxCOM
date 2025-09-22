<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* ====== SIDEBAR CONTAINER ====== */
        .sidebar-container {
            position: fixed !important;
            left: 20px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 80px !important;
            height: auto !important;
            z-index: 9999 !important;
            transition: all 0.3s ease !important;
            margin: 0 !important;
            padding: 0 !important;
            right: auto !important;
            bottom: auto !important;
        }
        
        .sidebar {
            width: 80px;
            background-color: #ffffff;
            padding: 20px 15px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 20px;
            border: 2px solid rgba(0, 0, 0, 0.178);
            position: relative;
            z-index: 1001;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            opacity: 0.95;
            overflow: hidden;
        }
        
        .sidebar:hover {
            width: 280px !important;
            opacity: 1;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar:hover .nav-buttons .btn span {
            opacity: 1 !important;
        }
        
        .sidebar-container:hover {
            width: 280px !important;
        }
        
        .nav-buttons {
            width: 100%;
            padding: 20px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 35px;
            align-items: center;
        }
        
        .nav-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 15px;
            background-color: #d88ef0;
            color: white;
            padding: 15px 20px;
            text-align: left;
            border-radius: 15px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-size: 16px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            width: 50px;
            min-width: 50px;
            white-space: nowrap;
        }
        
        .sidebar:hover .nav-buttons .btn {
            width: 240px !important;
            justify-content: flex-start !important;
        }
        
        .nav-buttons .btn i {
            font-size: 20px !important;
            opacity: 0.9 !important;
            min-width: 20px !important;
            text-align: center !important;
        }
        
        .nav-buttons .btn span {
            opacity: 0 !important;
            transition: opacity 0.3s ease !important;
            font-size: 14px !important;
        }
        
        .nav-buttons .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .nav-buttons .btn:hover::before {
            left: 100%;
        }
        
        .nav-buttons .btn:hover {
            background-color: #b963d1;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(185, 99, 209, 0.4);
            border-color: rgba(0, 0, 0, 0.3);
        }
        
        .nav-buttons .btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 12px rgba(185, 99, 209, 0.3);
        }
        
        .products-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 30px;
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }
        
        .products-header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .header-info h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-info p {
            opacity: 0.9;
            font-size: 1rem;
        }
        
        .header-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .btn-create {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 12px 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        
        .btn-create:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        .products-content {
            padding: 30px;
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .product-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #f8f9fa;
        }
        
        .no-image {
            width: 100%;
            height: 200px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 3rem;
        }
        
        .product-info {
            padding: 20px;
        }
        
        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            line-height: 1.3;
        }
        
        .product-marca {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .current-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #28a745;
        }
        
        .original-price {
            font-size: 1rem;
            color: #6c757d;
            text-decoration: line-through;
        }
        
        .product-stock {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }
        
        .stock-available {
            color: #28a745;
            font-weight: 600;
        }
        
        .stock-unavailable {
            color: #dc3545;
            font-weight: 600;
        }
        
        .product-actions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }
        
        .btn-action {
            flex: 1;
            padding: 10px 15px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
        }
        
        .btn-view {
            background: #007bff;
            color: white;
        }
        
        .btn-view:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }
        
        .btn-edit {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-edit:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .btn-delete:hover {
            background: #c82333;
            transform: translateY(-1px);
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .empty-state h3 {
            font-size: 1.5rem;
            margin-bottom: 10px;
            color: #495057;
        }
        
        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        
        .stats-bar {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #28a745;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .products-content {
                padding: 20px;
            }
            
            .products-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .header-actions {
                width: 100%;
                justify-content: center;
            }
            
            .product-actions {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <x-header-empresa />

    <div class="sidebar-container">
        <aside class="sidebar" id="sidebar">
            <nav class="nav-buttons">
                <a href="{{ route('empresa.dashboard') }}" class="btn">
                    <i class="fas fa-home"></i>
                    <span>Inicio</span>
                </a>
                <a href="{{ route('empresa.productos.index') }}" class="btn">
                    <i class="fas fa-box"></i>
                    <span>Productos</span>
                </a>
                <a href="{{ route('empresa.facturas') }}" class="btn">
                    <i class="fas fa-list-alt"></i>
                    <span>Log de Productos</span>
                </a>
                <form method="POST" action="{{ route('empresa.logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="btn" aria-label="Cerrar sesión">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Salir</span>
                    </button>
                </form>
            </nav>
        </aside>
    </div>

    <div class="main-container">

        <main class="dashboard-panel">
            <!-- Mensajes de sesión -->
            @if(session('success'))
                <div class="session-message success">
                    <div class="notification-icon">✓</div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('success') }}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="session-message error">
                    <div class="notification-icon">✕</div>
                    <div class="notification-content">
                        <div class="notification-message">{{ session('error') }}</div>
                    </div>
                    <button class="notification-close" onclick="this.parentElement.remove()">×</button>
                </div>
            @endif
            
            <div class="products-container">
                <div class="products-header">
                    <div class="header-info">
                        <h1>
                            <i class="fas fa-boxes"></i>
                            Gestión de Productos
                        </h1>
                        <p>Administra tu catálogo de productos</p>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('productos.create') }}" class="btn-create">
                            <i class="fas fa-plus"></i>
                            Nuevo Producto
                        </a>
                    </div>
                </div>

                <div class="products-content">
    @if ($productos->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-box-open"></i>
                            <h3>No tienes productos registrados</h3>
                            <p>Comienza agregando tu primer producto al catálogo</p>
                            <a href="{{ route('productos.create') }}" class="btn-create">
                                <i class="fas fa-plus"></i>
                                Crear Primer Producto
                            </a>
                        </div>
    @else
                        <!-- Estadísticas -->
                        <div class="stats-bar">
                            <div class="stat-item">
                                <div class="stat-value">{{ $productos->count() }}</div>
                                <div class="stat-label">Total Productos</div>
                            </div>
                            @php
                                $disponibles = $productos->filter(function($producto) {
                                    $fechaCaducidad = \Carbon\Carbon::parse($producto->Fecha_Caducidad);
                                    return $fechaCaducidad->isFuture() && $producto->Cantidad > 0;
                                })->count();
                                $noDisponibles = $productos->count() - $disponibles;
                            @endphp
                            <div class="stat-item">
                                <div class="stat-value">{{ $disponibles }}</div>
                                <div class="stat-label">Disponibles</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $noDisponibles }}</div>
                                <div class="stat-label">No Disponibles</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">${{ number_format($productos->sum('Precio'), 0, ',', '.') }}</div>
                                <div class="stat-label">Valor Total</div>
                            </div>
                        </div>

                        <!-- Grid de productos -->
                        <div class="products-grid">
            @foreach ($productos as $producto)
                                <div class="product-card">
                                    @if ($producto->Foto)
                                        <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                             alt="{{ $producto->Nombre }}" 
                                             class="product-image">
                                    @else
                                        <div class="no-image">
                                            <i class="fas fa-image"></i>
                                        </div>
                                    @endif
                                    
                                    <div class="product-info">
                                        <h3 class="product-name">{{ $producto->Nombre }}</h3>
                                        <p class="product-marca">{{ $producto->Marca }}</p>
                                        
                                        <div class="product-price">
                                            <span class="current-price">${{ number_format($producto->Precio, 0, ',', '.') }}</span>
                                            @if($producto->PrecioOriginal > $producto->Precio)
                                                <span class="original-price">${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</span>
                                            @endif
                                        </div>
                                        
                                        @php
                                            $fechaCaducidad = \Carbon\Carbon::parse($producto->Fecha_Caducidad);
                                            $hoy = \Carbon\Carbon::now();
                                            $estaDisponible = $fechaCaducidad->isFuture() && $producto->Cantidad > 0;
                                        @endphp
                                        <div class="product-stock">
                                            @if($estaDisponible)
                                                <i class="fas fa-check-circle"></i>
                                                <span class="stock-available">{{ $producto->Cantidad }} {{ $producto->Tipo }} disponibles</span>
                                            @else
                                                <i class="fas fa-times-circle"></i>
                                                <span class="stock-unavailable">
                                                    @if($fechaCaducidad->isPast())
                                                        Vencido
                                                    @else
                                                        Agotado
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="product-actions">
                                            <a href="{{ route('empresa.productos.show', $producto->Id_Producto) }}" class="btn-action btn-view">
                                                <i class="fas fa-eye"></i>
                                                Ver
                                            </a>
                                            <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="btn-action btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Editar
                                            </a>
                                            <form action="{{ route('productos.destroy', $producto->Id_Producto) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" onclick="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar
                                                </button>
                        </form>
                                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
                </div>
            </div>
        </main>
    </div>

    <!-- SCRIPT INMEDIATO PARA ALINEAR SIDEBAR A LA IZQUIERDA -->
    <script>
        // Ejecutar inmediatamente, antes de que se cargue cualquier otro script
        (function() {
            function forceLeftAlign() {
                const container = document.querySelector('.sidebar-container');
                if (container) {
                    container.style.cssText = `
                        position: fixed !important;
                        left: 20px !important;
                        top: 50% !important;
                        transform: translateY(-50%) !important;
                        width: 80px !important;
                        height: auto !important;
                        z-index: 9999 !important;
                        margin: 0 !important;
                        padding: 0 !important;
                        right: auto !important;
                        bottom: auto !important;
                    `;
                }
            }
            
            // Ejecutar inmediatamente
            forceLeftAlign();
            
            // Ejecutar cuando el DOM esté listo
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', forceLeftAlign);
            } else {
                forceLeftAlign();
            }
            
            // Ejecutar continuamente
            setInterval(forceLeftAlign, 100);
        })();
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad del sidebar alineado a la izquierda - FORZADO
        function forceLeftAlignSidebar() {
            const sidebarContainer = document.querySelector('.sidebar-container');
            if (sidebarContainer) {
                sidebarContainer.style.cssText = `
                    position: fixed !important;
                    left: 20px !important;
                    top: 50% !important;
                    transform: translateY(-50%) !important;
                    width: 80px !important;
                    height: auto !important;
                    z-index: 9999 !important;
                    margin: 0 !important;
                    padding: 0 !important;
                    right: auto !important;
                    bottom: auto !important;
                `;
            }
        }
        
        // Aplicar inmediatamente
        forceLeftAlignSidebar();
        
        // Aplicar cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', forceLeftAlignSidebar);
        
        // Aplicar cuando la ventana se carga
        window.addEventListener('load', forceLeftAlignSidebar);
        
        // Aplicar continuamente
        setInterval(forceLeftAlignSidebar, 50);
        
        // Aplicar en cualquier cambio
        const observer = new MutationObserver(forceLeftAlignSidebar);
        observer.observe(document.body, { childList: true, subtree: true });
    });
    </script>
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>