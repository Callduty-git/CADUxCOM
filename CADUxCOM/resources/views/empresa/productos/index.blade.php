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
        
        /* ====== SIDEBAR DESLIZABLE ====== */
        .sidebar {
            position: fixed;
            left: -250px; /* Oculto por defecto */
            top: 120px; /* Más abajo del header */
            transition: left 0.3s ease;
            z-index: 1000;
        }
        
        .sidebar:hover {
            left: 0; /* Se muestra al hacer hover */
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            left: 250px;
            top: 0;
            width: 20px;
            height: 100%;
            background: transparent;
            z-index: 1001;
        }
        
        .dashboard-panel {
            margin-left: 255px; /* 250px del sidebar + 5px de padding */
            transition: margin-left 0.3s ease;
        }
        
        /* Cuando el sidebar está visible, mantener el mismo padding */
        .sidebar:hover ~ .dashboard-panel {
            margin-left: 255px; /* Mismo padding de 5px siempre */
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

    <div class="main-container">
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
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
    });
    </script>
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>