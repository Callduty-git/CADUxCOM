<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $empresa->Nombre }} - CADUxCOM</title>
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Conoce {{ $empresa->Nombre }} y sus productos en CADUxCOM. Encuentra las mejores ofertas y productos de calidad.">
    <meta name="keywords" content="{{ $empresa->Nombre }}, productos, ofertas, CADUxCOM, empresa">
    <meta name="robots" content="index, follow">
    
    <!-- Estilos del header y footer -->
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Meta CSRF -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        /* Variables CSS con paleta CADUxCOM */
        :root {
            --primary-green: #89CF6D;
            --secondary-green: #49874E;
            --accent-purple: #AA5FC7;
            --white: #FFFFFF;
            --light-gray: #F8FAFC;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --border-color: #E2E8F0;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            --radius-md: 0.5rem;
            --radius-lg: 0.75rem;
            --transition-fast: 0.15s ease-in-out;
        }
        
        * {
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--light-gray);
            color: var(--text-primary);
            line-height: 1.6;
        }
        
        .empresa-page-container {
            max-width: 1200px;
            margin: 0 auto;
            margin-top: 80px;
            padding: 2rem 1rem;
        }
        
        .empresa-header {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            color: var(--white);
            padding: 3rem 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
            text-align: center;
            box-shadow: var(--shadow-lg);
        }
        
        .empresa-logo {
            width: 120px;
            height: 120px;
            background: var(--white);
            border-radius: 50%;
            margin: 0 auto 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--shadow-md);
        }
        
        .empresa-logo img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 50%;
        }
        
        .empresa-logo i {
            font-size: 3rem;
            color: var(--primary-green);
        }
        
        .empresa-title {
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 0 0.5rem 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .empresa-subtitle {
            font-size: 1.125rem;
            opacity: 0.9;
            margin: 0;
        }
        
        .empresa-info {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-md);
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem;
            background: var(--light-gray);
            border-radius: var(--radius-md);
        }
        
        .info-item i {
            font-size: 1.25rem;
            color: var(--primary-green);
            width: 24px;
            text-align: center;
        }
        
        .info-item span {
            font-weight: 500;
            color: var(--text-primary);
        }
        
        .products-section {
            background: var(--white);
            padding: 2rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 1.5rem 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .section-title i {
            color: var(--primary-green);
        }
        
        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
        }
        
        .product-card {
            background: var(--white);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: all var(--transition-fast);
            position: relative;
            overflow: hidden;
        }
        
        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-purple);
            transform: scaleY(0);
            transition: transform var(--transition-fast);
        }
        
        .product-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-purple);
        }
        
        .product-card:hover::before {
            transform: scaleY(1);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;
            border: 1px solid var(--border-color);
        }
        
        .product-name {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
            line-height: 1.4;
        }
        
        .product-description {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin: 0 0 1rem 0;
            line-height: 1.5;
        }
        
        .product-price {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
            flex-wrap: wrap;
        }
        
        .price-discounted {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--secondary-green);
        }
        
        .price-original {
            font-size: 1rem;
            text-decoration: line-through;
            color: var(--text-secondary);
        }
        
        .price-normal {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
        }
        
        .discount-badge {
            background: var(--primary-green);
            color: var(--white);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-md);
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .product-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }
        
        .product-category {
            background: var(--light-gray);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-md);
            font-weight: 500;
        }
        
        .product-quantity {
            font-weight: 500;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.5rem;
            background: var(--secondary-green);
            color: var(--white);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all var(--transition-fast);
            margin-bottom: 2rem;
            position: sticky;
            top: 100px;
            z-index: 100;
            box-shadow: var(--shadow-md);
        }
        
        .back-button:hover {
            background: var(--primary-green);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        /* Botón flotante para móviles */
        .floating-back-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: var(--secondary-green);
            color: var(--white);
            padding: 1rem;
            border-radius: 50%;
            text-decoration: none;
            box-shadow: var(--shadow-lg);
            z-index: 1000;
            display: none;
            transition: all var(--transition-fast);
        }
        
        .floating-back-button:hover {
            background: var(--primary-green);
            transform: scale(1.1);
        }
        
        .floating-back-button i {
            font-size: 1.25rem;
        }
        
        .no-products {
            text-align: center;
            padding: 3rem 2rem;
            color: var(--text-secondary);
        }
        
        .no-products i {
            font-size: 3rem;
            color: var(--primary-green);
            margin-bottom: 1rem;
        }
        
        .no-products h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.5rem 0;
        }
        
        .no-products p {
            margin: 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .empresa-page-container {
                margin-top: 60px;
                padding: 1rem 0.5rem;
            }
            
            .back-button {
                position: relative;
                top: auto;
                margin-bottom: 1rem;
            }
            
            .floating-back-button {
                display: block;
            }
            
            .empresa-header {
                padding: 2rem 1rem;
            }
            
            .empresa-title {
                font-size: 2rem;
            }
            
            .empresa-logo {
                width: 100px;
                height: 100px;
            }
            
            .empresa-logo i {
                font-size: 2.5rem;
            }
            
            .empresa-info,
            .products-section {
                padding: 1.5rem;
            }
            
            .products-grid {
                grid-template-columns: 1fr;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .empresa-page-container {
                margin-top: 50px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <x-header-pages />
    
    <!-- Contenido principal -->
    <div class="empresa-page-container">
        <!-- Botón de regreso -->
        <a href="{{ url('/mapa') }}" class="back-button">
            <i class="fas fa-arrow-left"></i>
            Volver al Mapa
        </a>
        
        <!-- Header de la empresa -->
        <div class="empresa-header">
            <div class="empresa-logo">
                @if($empresa->Foto)
                    <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="{{ $empresa->Nombre }}">
                @else
                    <i class="fas fa-store"></i>
                @endif
            </div>
            <h1 class="empresa-title">{{ $empresa->Nombre }}</h1>
            <p class="empresa-subtitle">Descubre nuestros productos y ofertas especiales</p>
        </div>
        
        <!-- Información de la empresa -->
        <div class="empresa-info">
            <div class="info-grid">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $empresa->Direccion ?? 'Dirección no disponible' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $empresa->Telefono ?? 'Teléfono no disponible' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $empresa->Email ?? 'Email no disponible' }}</span>
                </div>
                <div class="info-item">
                    <i class="fas fa-box"></i>
                    <span>{{ count($productos) }} productos disponibles</span>
                </div>
            </div>
            
            @if($empresa->Descripcion)
                <div class="empresa-description">
                    <h3 style="color: var(--text-primary); margin-bottom: 1rem;">
                        <i class="fas fa-info-circle" style="color: var(--primary-green);"></i>
                        Sobre nosotros
                    </h3>
                    <p style="color: var(--text-secondary); line-height: 1.6; margin: 0;">
                        {{ $empresa->Descripcion }}
                    </p>
                </div>
            @endif
        </div>
        
        <!-- Productos -->
        <div class="products-section">
            <h2 class="section-title">
                <i class="fas fa-box"></i>
                Nuestros Productos
            </h2>
            
            @if(count($productos) > 0)
                <div class="products-grid">
                    @foreach($productos as $producto)
                        <div class="product-card">
                            <img src="{{ $producto['image'] }}" alt="{{ $producto['name'] }}" class="product-image">
                            
                            <h3 class="product-name">{{ $producto['name'] }}</h3>
                            
                            @if($producto['description'])
                                <p class="product-description">{{ $producto['description'] }}</p>
                            @endif
                            
                            <div class="product-price">
                                @if($producto['has_discount'])
                                    <span class="price-discounted">${{ number_format($producto['discounted_price']) }}</span>
                                    <span class="price-original">${{ number_format($producto['price']) }}</span>
                                    <span class="discount-badge">-{{ $producto['discount_percentage'] }}%</span>
                                @else
                                    <span class="price-normal">${{ number_format($producto['price']) }}</span>
                                @endif
                            </div>
                            
                            <div class="product-meta">
                                <span class="product-category">{{ $producto['category'] }}</span>
                                <span class="product-quantity">{{ $producto['quantity'] }} disponibles</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="no-products">
                    <i class="fas fa-box-open"></i>
                    <h3>No hay productos disponibles</h3>
                    <p>Esta empresa aún no ha agregado productos a su catálogo.</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Botón flotante para móviles -->
    <a href="{{ url('/mapa') }}" class="floating-back-button" title="Volver al Mapa">
        <i class="fas fa-arrow-left"></i>
    </a>
    
    <!-- Footer -->
    <x-footer />
</body>
</html>
