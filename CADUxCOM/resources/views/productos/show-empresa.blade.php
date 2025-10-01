<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Producto - {{ $producto->Nombre }} - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            padding-top: 110px; /* Compensar el header fixed */
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
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .product-detail-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 0;
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }
        
        .product-detail-header {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: white;
            padding: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .product-info {
            flex: 1;
        }
        
        .product-title {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .product-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 15px;
        }
        
        .product-status {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .status-active {
            background: rgba(40, 167, 69, 0.2);
            color: #28a745;
            border: 2px solid #28a745;
        }
        
        .status-inactive {
            background: rgba(220, 53, 69, 0.2);
            color: #dc3545;
            border: 2px solid #dc3545;
        }
        
        .product-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }
        
        .btn-action {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, #ffc107 0%, #ff8c00 100%);
            color: white;
        }
        
        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(255, 193, 7, 0.3);
        }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(220, 53, 69, 0.3);
        }
        
        .product-detail-content {
            padding: 40px;
        }
        
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        
        .detail-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid #e9ecef;
        }
        
        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #495057;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
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
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .original-price {
            font-size: 1.2rem;
            opacity: 0.8;
            text-decoration: line-through;
            margin-bottom: 10px;
        }
        
        .discount-info {
            background: rgba(255, 255, 255, 0.2);
            padding: 10px 20px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 600;
        }
        
        .image-section {
            text-align: center;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            border: 1px solid #e9ecef;
        }
        
        .product-image {
            max-width: 100%;
            max-height: 300px;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .product-image:hover {
            transform: scale(1.05);
        }
        
        .no-image {
            width: 200px;
            height: 200px;
            background: #e9ecef;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #6c757d;
            font-size: 3rem;
        }
        
        .stats-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px;
            margin: 20px 0;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .stat-item {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            backdrop-filter: blur(10px);
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
        }
        
        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 15px 25px;
            background: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            margin-top: 30px;
        }
        
        .back-button:hover {
            background: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 117, 125, 0.3);
        }
        
        @media (max-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .product-detail-content {
                padding: 20px;
            }
            
            .product-detail-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
            
            .product-actions {
                flex-direction: column;
                width: 100%;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <x-header-empresa />


    <div class="main-container">
        <main class="dashboard-panel">
            <div class="product-detail-container">
                <!-- Header del producto -->
                <div class="product-detail-header">
                    <div class="product-info">
                        <h1 class="product-title">{{ $producto->Nombre }}</h1>
                        <p class="product-subtitle">Código: {{ $producto->Codigo }} | Marca: {{ $producto->Marca }}</p>
                        @php
                            $fechaCaducidad = \Carbon\Carbon::parse($producto->Fecha_Caducidad);
                            $hoy = \Carbon\Carbon::now();
                            $estaDisponible = $fechaCaducidad->isFuture() && $producto->Cantidad > 0;
                        @endphp
                        <div class="product-status {{ $estaDisponible ? 'status-active' : 'status-inactive' }}">
                            <i class="fas {{ $estaDisponible ? 'fa-check-circle' : 'fa-times-circle' }}"></i>
                            {{ $estaDisponible ? 'Disponible' : 'No Disponible' }}
                        </div>
                    </div>
                    <div class="product-actions">
                        <a href="{{ route('productos.edit', $producto->Id_Producto) }}" class="btn-action btn-edit">
                            <i class="fas fa-edit"></i>
                            Editar
                        </a>
                        <form method="POST" action="{{ route('productos.destroy', $producto->Id_Producto) }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este producto?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete">
                                <i class="fas fa-trash"></i>
                                Eliminar
                            </button>
                        </form>
                    </div>
                </div>

                <div class="product-detail-content">
                    <div class="detail-grid">
                        <!-- Imagen del producto -->
                        <div class="image-section">
                            <h3 class="section-title">
                                <i class="fas fa-image"></i>
                                Imagen del Producto
                            </h3>
                            @if ($producto->Foto)
                                <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                     alt="{{ $producto->Nombre }}" 
                                     class="product-image">
                            @else
                                <div class="no-image">
                                    <i class="fas fa-image"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Información básica -->
                        <div class="detail-section">
                            <h3 class="section-title">
                                <i class="fas fa-info-circle"></i>
                                Información Básica
                            </h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Nombre</div>
                                    <div class="info-value">{{ $producto->Nombre }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Marca</div>
                                    <div class="info-value">{{ $producto->Marca }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Código</div>
                                    <div class="info-value">{{ $producto->Codigo }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Tipo/Unidad</div>
                                    <div class="info-value">{{ $producto->Tipo }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Categoría</div>
                                    <div class="info-value">{{ $producto->subcategoria->categoria->Nombre ?? 'N/A' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Subcategoría</div>
                                    <div class="info-value">{{ $producto->subcategoria->Nombre ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="price-section">
                        <h3 style="margin-bottom: 20px; font-size: 1.5rem;">
                            <i class="fas fa-dollar-sign"></i>
                            Información de Precios
                        </h3>
                        @if($producto->PrecioOriginal > $producto->Precio)
                            <div class="current-price">${{ number_format($producto->Precio, 0, ',', '.') }}</div>
                            <div class="original-price">Precio original: ${{ number_format($producto->PrecioOriginal, 0, ',', '.') }}</div>
                            <div class="discount-info">
                                Descuento: {{ number_format((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100, 0) }}%
                            </div>
                        @else
                            <div class="current-price">${{ number_format($producto->Precio, 0, ',', '.') }}</div>
                            <div class="discount-info">Precio sin descuento</div>
                        @endif
                    </div>

                    <!-- Estadísticas del producto -->
                    <div class="stats-section">
                        <h3 style="margin-bottom: 20px; font-size: 1.5rem;">
                            <i class="fas fa-chart-bar"></i>
                            Estadísticas del Producto
                        </h3>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-value">{{ $producto->Cantidad }}</div>
                                <div class="stat-label">En Stock</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value" style="color: {{ $estaDisponible ? '#28a745' : '#dc3545' }};">
                                    {{ $estaDisponible ? 'SÍ' : 'NO' }}
                                </div>
                                <div class="stat-label">Disponible</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">${{ number_format($producto->Precio * $producto->Cantidad, 0, ',', '.') }}</div>
                                <div class="stat-label">Valor Total</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-value">{{ $producto->PrecioOriginal > $producto->Precio ? number_format((($producto->PrecioOriginal - $producto->Precio) / $producto->PrecioOriginal) * 100, 0) . '%' : '0%' }}</div>
                                <div class="stat-label">Descuento</div>
                            </div>
                            <div class="stat-item">
                                @if($producto->Fecha_Caducidad)
                                    <div class="stat-value" id="countdown-timer">
                                        <span id="countdown-text">Cargando...</span>
                                    </div>
                                    <div class="stat-label">Estado de Caducidad</div>
                                    <script>
                                        // Fecha de caducidad del producto
                                        const fechaCaducidad = new Date('{{ $producto->Fecha_Caducidad }}');
                                        
                                        function updateCountdown() {
                                            const ahora = new Date();
                                            const diferencia = fechaCaducidad - ahora;
                                            
                                            if (diferencia > 0) {
                                                // Producto aún no vence
                                                const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                                                const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                                                const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
                                                
                                                if (dias >= 1) {
                                                    document.getElementById('countdown-text').textContent = `Faltan ${dias} días`;
                                                } else {
                                                    document.getElementById('countdown-text').textContent = `Faltan ${horas}h ${minutos}m ${segundos}s`;
                                                }
                                            } else {
                                                // Producto vencido
                                                const diasVencido = Math.floor(Math.abs(diferencia) / (1000 * 60 * 60 * 24));
                                                document.getElementById('countdown-text').textContent = `Vencido hace ${diasVencido} días`;
                                            }
                                        }
                                        
                                        // Actualizar inmediatamente
                                        updateCountdown();
                                        
                                        // Actualizar cada segundo
                                        setInterval(updateCountdown, 1000);
                                    </script>
                                @else
                                    <div class="stat-value">N/A</div>
                                    <div class="stat-label">Estado de Caducidad</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Información adicional -->
                    <div class="detail-section">
                        <h3 class="section-title">
                            <i class="fas fa-list-alt"></i>
                            Información Adicional
                        </h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Descripción</div>
                                <div class="info-value">{{ $producto->Descripcion ?? 'Sin descripción' }}</div>
                            </div>
                            @if($producto->Fecha_Caducidad)
                                <div class="info-item">
                                    <div class="info-label">Fecha de Caducidad</div>
                                    <div class="info-value" style="font-weight: bold;">
                                        {{ \Carbon\Carbon::parse($producto->Fecha_Caducidad)->format('d/m/Y') }}
                                        <span id="countdown-detail" style="color: #28a745;">(Cargando...)</span>
                                    </div>
                                    <script>
                                        // Reutilizar la misma lógica para la sección de información
                                        function updateCountdownDetail() {
                                            const ahora = new Date();
                                            const diferencia = fechaCaducidad - ahora;
                                            const countdownElement = document.getElementById('countdown-detail');
                                            
                                            if (diferencia > 0) {
                                                // Producto aún no vence
                                                const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
                                                const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                                const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
                                                const segundos = Math.floor((diferencia % (1000 * 60)) / 1000);
                                                
                                                countdownElement.style.color = '#28a745';
                                                
                                                if (dias >= 1) {
                                                    countdownElement.textContent = `(Faltan ${dias} días)`;
                                                } else {
                                                    countdownElement.textContent = `(Faltan ${horas}h ${minutos}m ${segundos}s)`;
                                                }
                                            } else {
                                                // Producto vencido
                                                const diasVencido = Math.floor(Math.abs(diferencia) / (1000 * 60 * 60 * 24));
                                                countdownElement.style.color = '#dc3545';
                                                countdownElement.textContent = `(Vencido hace ${diasVencido} días)`;
                                            }
                                        }
                                        
                                        // Actualizar inmediatamente
                                        updateCountdownDetail();
                                        
                                        // Actualizar cada segundo
                                        setInterval(updateCountdownDetail, 1000);
                                    </script>
                                </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">Empresa</div>
                                <div class="info-value">{{ $producto->empresa->Nombre ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Fecha de Creación</div>
                                <div class="info-value">{{ $producto->created_at ? $producto->created_at->format('d/m/Y H:i') : 'N/A' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de regreso -->
                    <div style="text-align: center;">
                        <a href="{{ route('empresa.productos.index') }}" class="back-button">
                            <i class="fas fa-arrow-left"></i>
                            Volver a la Lista de Productos
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>
