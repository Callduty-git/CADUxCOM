<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $producto->Nombre }} - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .product-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 20px 0;
        }
        
        .product-image { 
            transition: transform 0.3s ease; 
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        .product-image:hover { 
            transform: scale(1.05); 
            box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
        }
        
        .price-original { 
            text-decoration: line-through; 
            color: #6c757d;
        }
        
        .discount-badge { 
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%);
            color: white;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.875rem;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
        }
        
        .product-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .product-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .product-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .product-content {
            padding: 40px;
        }
        
        .info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }
        
        .info-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .info-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            background: white;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #e9ecef;
        }
        
        .info-label {
            font-weight: 600;
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #495057;
            font-size: 1.1rem;
        }
        
        .price-section {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            margin: 20px 0;
        }
        
        .current-price {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .stock-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: 600;
            margin: 15px 0;
        }
        
        .stock-available {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 2px solid #28a745;
        }
        
        .stock-unavailable {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 2px solid #dc3545;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(40, 167, 69, 0.3);
        }
        
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
        }
        
        .company-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin-top: 20px;
        }
        
        .company-title {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .company-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .company-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        
        .company-label {
            font-size: 0.9rem;
            opacity: 0.8;
            margin-bottom: 5px;
        }
        
        .company-value {
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .product-content {
                padding: 20px;
            }
            
            .product-title {
                font-size: 2rem;
            }
            
            .current-price {
                font-size: 2.5rem;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="page-container">
        <x-header-pages />
        
        <main class="content min-h-screen py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Breadcrumb -->
                <nav class="mb-6">
                    <ol class="flex items-center space-x-2 text-sm text-gray-600">
                        <li><a href="{{ route('home') }}" class="hover:text-gray-800">Inicio</a></li>
                        <li>/</li>
                        <li><a href="{{ route('productos.public.index') }}" class="hover:text-gray-800">Productos</a></li>
                        <li>/</li>
                        <li class="text-gray-900 font-medium">{{ $producto->Nombre }}</li>
                    </ol>
                </nav>

                <div class="product-container">
                    <!-- Header del producto -->
                    <div class="product-header">
                        <h1 class="product-title">{{ $producto->Nombre }}</h1>
                        <p class="product-subtitle">por {{ $producto->empresa->Nombre ?? 'Empresa no disponible' }}</p>
                    </div>

                    <div class="product-content">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            <!-- Imagen del producto -->
                            <div class="space-y-4">
                                <div class="aspect-square bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                                    @if ($producto->Foto)
                                        <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                             alt="{{ $producto->Nombre }}" 
                                             class="product-image w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                            <i class="fas fa-image text-6xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Información del producto -->
                            <div class="space-y-6">
                                <!-- Precios -->
                                <div class="price-section">
                                    @if($producto->PrecioOriginal > $producto->Precio)
                                        <div class="current-price">${{ number_format($producto->Precio, 0, ',', '.') }}</div>
                                        <div class="price-original text-xl opacity-80">Precio original: ${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</div>
                                        <div class="discount-badge">
                                            -{{ number_format((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100, 0) }}% OFF
                                        </div>
                                    @else
                                        <div class="current-price">${{ number_format($producto->Precio, 0, ',', '.') }}</div>
                                    @endif
                                </div>

                                <!-- Stock -->
                                <div class="text-center">
                                    @if($producto->Cantidad > 0)
                                        <div class="stock-status stock-available">
                                            <i class="fas fa-check-circle"></i>
                                            Disponible ({{ $producto->Cantidad }} {{ $producto->Tipo }} en stock)
                                        </div>
                                    @else
                                        <div class="stock-status stock-unavailable">
                                            <i class="fas fa-times-circle"></i>
                                            Agotado
                                        </div>
                                    @endif
                                </div>

                                <!-- Agregar al carrito -->
                                @if($producto->Cantidad > 0)
                                    <div class="border-t border-gray-200 pt-6">
                                        <x-add-to-cart :product="$producto" />
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información detallada -->
                        <div class="info-card">
                            <h3 class="info-title">
                                <i class="fas fa-info-circle"></i>
                                Descripción del Producto
                            </h3>
                            <p class="text-gray-600 text-lg">{{ $producto->Descripcion ?? 'Sin descripción disponible' }}</p>
                        </div>

                        <!-- Detalles del producto -->
                        <div class="info-card">
                            <h3 class="info-title">
                                <i class="fas fa-list-alt"></i>
                                Detalles del Producto
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Marca</div>
                                    <div class="info-value">{{ $producto->Marca }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Código</div>
                                    <div class="info-value">{{ $producto->Codigo }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Categoría</div>
                                    <div class="info-value">{{ $producto->subcategoria->categoria->Nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Subcategoría</div>
                                    <div class="info-value">{{ $producto->subcategoria->Nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Tipo/Unidad</div>
                                    <div class="info-value">{{ $producto->Tipo }}</div>
                                </div>
                                @if($producto->Fecha_Caducidad)
                                    <div class="info-item">
                                        <div class="info-label">Fecha de Caducidad</div>
                                        <div class="info-value">{{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Información de la empresa -->
                        <div class="company-info">
                            <h3 class="company-title">
                                <i class="fas fa-store"></i>
                                Información del Vendedor
                            </h3>
                            <div class="company-details">
                                <div class="company-item">
                                    <div class="company-label">Empresa</div>
                                    <div class="company-value">{{ $producto->empresa->Nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="company-item">
                                    <div class="company-label">Contacto</div>
                                    <div class="company-value">{{ $producto->empresa->Contacto ?? 'N/A' }}</div>
                                </div>
                                <div class="company-item">
                                    <div class="company-label">Ubicación</div>
                                    <div class="company-value">{{ $producto->empresa->Ubicacion ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de acción -->
                        <div class="action-buttons">
                            <a href="{{ route('productos.public.index') }}" class="btn-action btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Volver a Productos
                            </a>
                            
                            @auth
                                @if(Auth::guard('empresa')->check() && Auth::guard('empresa')->user()->Id_Empresa == $producto->Id_Empresa)
                                    <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="btn-action btn-warning">
                                        <i class="fas fa-edit"></i>
                                        Editar Producto
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <x-footer />
    </div>
</body>
</html>