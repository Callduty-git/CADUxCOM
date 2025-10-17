<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Pedidos - Centro de Ayuda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/header-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .orders-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .orders-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .orders-header h1 {
            color: #49874E;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .orders-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #49874E;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #89CF6D;
        }

        .back-link::before {
            content: '←';
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        .orders-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #89CF6D;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: #49874E;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        .orders-list {
            background: #FFFFFF;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .order-item {
            border-bottom: 1px solid #e9ecef;
            padding: 2rem;
            transition: background-color 0.3s ease;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background-color: #f8f9fa;
        }

        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .order-number {
            font-size: 1.2rem;
            font-weight: 600;
            color: #49874E;
        }

        .order-date {
            color: #666;
            font-size: 0.9rem;
        }

        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-shipped {
            background-color: #e2e3e5;
            color: #383d41;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-refunded {
            background-color: #ffeaa7;
            color: #6c5ce7;
        }

        .order-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .order-detail {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            font-weight: 500;
            color: #333;
        }

        .order-items {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e9ecef;
        }

        .order-items h4 {
            color: #49874E;
            margin-bottom: 0.5rem;
            font-size: 1rem;
        }

        .item-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .item-list li {
            padding: 0.25rem 0;
            color: #666;
            font-size: 0.9rem;
        }

        .order-actions {
            margin-top: 1rem;
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background-color: #89CF6D;
            color: white;
        }

        .btn-primary:hover {
            background-color: #49874E;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .btn-danger {
            background-color: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background-color: #c82333;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state img {
            width: 100px;
            height: 100px;
            margin-bottom: 2rem;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #49874E;
            margin-bottom: 1rem;
        }

        .empty-state p {
            margin-bottom: 2rem;
        }

        .filters {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .filters h3 {
            color: #49874E;
            margin-bottom: 1rem;
            font-size: 1.1rem;
        }

        .filter-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-select {
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: white;
        }

        @media (max-width: 768px) {
            .orders-container {
                padding: 1rem;
            }
            
            .orders-header h1 {
                font-size: 2rem;
            }
            
            .order-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .order-details {
                grid-template-columns: 1fr;
            }
            
            .order-actions {
                flex-direction: column;
            }
            
            .filter-group {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />

        <div class="help-container">
            <div class="orders-container">
                <a href="{{ route('help') }}" class="back-link">Volver al Centro de Ayuda</a>
                
                <div class="orders-header">
                    <h1>Mis Pedidos</h1>
                    <p>Rastrea y gestiona todas tus compras</p>
                </div>

                @auth('web')
                    @if($orders->count() > 0)
                        <!-- Estadísticas de pedidos -->
                        <div class="orders-stats">
                            <div class="stat-card">
                                <div class="stat-number">{{ $orders->count() }}</div>
                                <div class="stat-label">Total de Pedidos</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $orders->where('status', 'delivered')->count() }}</div>
                                <div class="stat-label">Entregados</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">{{ $orders->whereIn('status', ['pending', 'paid', 'processing', 'shipped'])->count() }}</div>
                                <div class="stat-label">En Proceso</div>
                            </div>
                            <div class="stat-card">
                                <div class="stat-number">${{ number_format($orders->sum('total_amount'), 0, ',', '.') }}</div>
                                <div class="stat-label">Total Gastado</div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="filters">
                            <h3>Filtrar Pedidos</h3>
                            <div class="filter-group">
                                <select class="filter-select" id="statusFilter">
                                    <option value="">Todos los estados</option>
                                    <option value="pending">Pendiente</option>
                                    <option value="paid">Pagado</option>
                                    <option value="processing">En Procesamiento</option>
                                    <option value="shipped">Enviado</option>
                                    <option value="delivered">Entregado</option>
                                    <option value="cancelled">Cancelado</option>
                                    <option value="refunded">Reembolsado</option>
                                </select>
                                <select class="filter-select" id="dateFilter">
                                    <option value="">Todas las fechas</option>
                                    <option value="last_week">Última semana</option>
                                    <option value="last_month">Último mes</option>
                                    <option value="last_3_months">Últimos 3 meses</option>
                                    <option value="last_year">Último año</option>
                                </select>
                            </div>
                        </div>

                        <!-- Lista de pedidos -->
                        <div class="orders-list">
                            @foreach($orders as $order)
                                <div class="order-item" data-status="{{ $order->status }}" data-date="{{ $order->created_at->format('Y-m-d') }}">
                                    <div class="order-header">
                                        <div>
                                            <div class="order-number">Pedido #{{ $order->order_number }}</div>
                                            <div class="order-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                        <div class="order-status status-{{ $order->status }}">
                                            {{ $order->getStatusInSpanish() }}
                                        </div>
                                    </div>

                                    <div class="order-details">
                                        <div class="order-detail">
                                            <span class="detail-label">Total</span>
                                            <span class="detail-value">${{ number_format($order->total_amount, 0, ',', '.') }}</span>
                                        </div>
                                        <div class="order-detail">
                                            <span class="detail-label">Método de Pago</span>
                                            <span class="detail-value">
                                                @switch($order->payment_method)
                                                    @case('credit_card')
                                                        Tarjeta de Crédito
                                                        @break
                                                    @case('debit_card')
                                                        Tarjeta de Débito
                                                        @break
                                                    @case('bank_transfer')
                                                        Transferencia Bancaria
                                                        @break
                                                    @case('digital_wallet')
                                                        Billetera Digital
                                                        @break
                                                    @default
                                                        {{ $order->payment_method }}
                                                @endswitch
                                            </span>
                                        </div>
                                        <div class="order-detail">
                                            <span class="detail-label">Dirección de Envío</span>
                                            <span class="detail-value">{{ $order->shipping_address }}, {{ $order->shipping_city }}</span>
                                        </div>
                                        @if($order->tracking_number)
                                            <div class="order-detail">
                                                <span class="detail-label">Número de Seguimiento</span>
                                                <span class="detail-value">{{ $order->tracking_number }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    @if($order->items->count() > 0)
                                        <div class="order-items">
                                            <h4>Productos ({{ $order->items->count() }} {{ $order->items->count() == 1 ? 'artículo' : 'artículos' }})</h4>
                                            <ul class="item-list">
                                                @foreach($order->items->take(3) as $item)
                                                    <li>{{ $item->quantity }}x {{ $item->product_name }} - ${{ number_format($item->unit_price, 0, ',', '.') }}</li>
                                                @endforeach
                                                @if($order->items->count() > 3)
                                                    <li>... y {{ $order->items->count() - 3 }} productos más</li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif

                                    <div class="order-actions">
                                        <a href="#" class="btn btn-primary">Ver Detalles</a>
                                        @if($order->status === 'delivered')
                                            <a href="#" class="btn btn-secondary">Descargar Factura</a>
                                        @endif
                                        @if($order->canBeCancelled())
                                            <button class="btn btn-danger" onclick="cancelOrder('{{ $order->id }}')">Cancelar Pedido</button>
                                        @endif
                                        @if($order->status === 'delivered')
                                            <a href="#" class="btn btn-secondary">Reordenar</a>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Paginación -->
                        @if($orders->hasPages())
                            <div style="margin-top: 2rem;">
                                {{ $orders->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Estado vacío -->
                        <div class="orders-list">
                            <div class="empty-state">
                                <img src="{{ asset('images/carrito-de-compras.png') }}" alt="Sin pedidos">
                                <h3>No tienes pedidos aún</h3>
                                <p>Cuando realices tu primera compra, aparecerá aquí para que puedas hacer seguimiento.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Explorar Productos</a>
                            </div>
                        </div>
                    @endif
                @endauth

                @auth('empresa')
                    @if(!auth()->check())
                        <!-- Mensaje para empresas -->
                        <div class="orders-list">
                            <div class="empty-state">
                                <img src="{{ asset('images/icon-user.png') }}" alt="Empresa">
                                <h3>Gestión de Ventas Empresariales</h3>
                                <p>Como empresa, puedes gestionar tus ventas desde tu dashboard empresarial.</p>
                                <a href="{{ route('empresa.dashboard') }}" class="btn btn-primary">Ir al Dashboard</a>
                            </div>
                        </div>
                    @endif
                @endauth

                @guest
                    <!-- Usuario no autenticado -->
                    <div class="orders-list">
                        <div class="empty-state">
                            <img src="{{ asset('images/icon-user.png') }}" alt="Login requerido">
                            <h3>Inicia sesión para ver tus pedidos</h3>
                            <p>Para acceder a tu historial de pedidos, necesitas iniciar sesión en tu cuenta.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary">Iniciar Sesión</a>
                        </div>
                    </div>
                @endguest
            </div>
        </div>

        <x-footer />
    </div>

    <script>
        // Filtros
        document.getElementById('statusFilter').addEventListener('change', function() {
            filterOrders();
        });

        document.getElementById('dateFilter').addEventListener('change', function() {
            filterOrders();
        });

        function filterOrders() {
            const statusFilter = document.getElementById('statusFilter').value;
            const dateFilter = document.getElementById('dateFilter').value;
            const orderItems = document.querySelectorAll('.order-item');

            orderItems.forEach(item => {
                let showItem = true;

                // Filtro por estado
                if (statusFilter && item.dataset.status !== statusFilter) {
                    showItem = false;
                }

                // Filtro por fecha
                if (dateFilter && showItem) {
                    const orderDate = new Date(item.dataset.date);
                    const now = new Date();
                    let cutoffDate = new Date();

                    switch (dateFilter) {
                        case 'last_week':
                            cutoffDate.setDate(now.getDate() - 7);
                            break;
                        case 'last_month':
                            cutoffDate.setMonth(now.getMonth() - 1);
                            break;
                        case 'last_3_months':
                            cutoffDate.setMonth(now.getMonth() - 3);
                            break;
                        case 'last_year':
                            cutoffDate.setFullYear(now.getFullYear() - 1);
                            break;
                    }

                    if (orderDate < cutoffDate) {
                        showItem = false;
                    }
                }

                item.style.display = showItem ? 'block' : 'none';
            });
        }

        // Función para cancelar pedido
        function cancelOrder(orderId) {
            if (confirm('¿Estás seguro de que quieres cancelar este pedido?')) {
                // Aquí iría la lógica para cancelar el pedido
                alert('Funcionalidad de cancelación pendiente de implementar');
            }
        }
    </script>
</body>
</html>