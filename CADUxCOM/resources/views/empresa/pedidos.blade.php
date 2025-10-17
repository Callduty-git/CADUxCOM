<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedidos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-empresa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            padding: 0;
            line-height: 1.6;
        }

        .main-content {
            flex: 1;
        }

        .header {
            border-bottom: 3px solid #006400;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .page-header {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .page-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .filters-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
        }

        .filter-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 5px;
        }

        .filter-input {
            padding: 10px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .filter-input:focus {
            outline: none;
            border-color: #89CF6D;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #89CF6D;
            color: white;
        }

        .btn-primary:hover {
            background: #49874E;
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .orders-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            border-bottom: 2px solid #e5e7eb;
        }

        .table td {
            padding: 15px;
            border-bottom: 1px solid #e5e7eb;
            color: #666;
        }

        .table tr:hover {
            background: #f8f9fa;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
            color: white;
        }

        .status-pending { background: #fbbf24; }
        .status-paid { background: #10b981; }
        .status-processing { background: #3b82f6; }
        .status-shipped { background: #8b5cf6; }
        .status-delivered { background: #059669; }
        .status-cancelled { background: #ef4444; }

        .pagination-wrapper {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .no-orders {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }

        .no-orders i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #89CF6D;
            text-decoration: none;
            font-weight: 600;
            margin-bottom: 20px;
            transition: color 0.3s;
        }

        .back-link:hover {
            color: #49874E;
        }
    </style>
</head>

<body>
    @include('components.header-empresa')

    <div class="main-content">
        <div class="container">
            <a href="{{ route('empresa.dashboard') }}" class="back-link">
                <i class="fas fa-arrow-left"></i>
                Volver al Dashboard
            </a>

            <div class="page-header">
                <h1 class="page-title">
                    <i class="fas fa-shopping-bag"></i>
                    Gestión de Pedidos
                </h1>
                <p class="page-subtitle">Administra todos los pedidos de tus productos</p>
            </div>

            <!-- Filtros -->
            <div class="filters-section">
                <form method="GET" action="{{ route('empresa.pedidos') }}">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label class="filter-label">Estado del Pedido</label>
                            <select name="status" class="filter-input">
                                <option value="">Todos los estados</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagado</option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Procesando</option>
                                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label class="filter-label">Fecha</label>
                            <input type="date" name="fecha" value="{{ request('fecha') }}" class="filter-input">
                        </div>
                        <div class="filter-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                                Filtrar
                            </button>
                        </div>
                        <div class="filter-group">
                            <a href="{{ route('empresa.pedidos') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tabla de Pedidos -->
            @if($pedidos->count() > 0)
                <div class="orders-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Producto</th>
                                <th>Cliente</th>
                                <th>Email Cliente</th>
                                <th>Teléfono</th>
                                <th>Cantidad</th>
                                <th>Precio Unit.</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pedidos as $pedido)
                                <tr>
                                    <td style="font-weight: 600; color: #333;">#{{ $pedido->order->id }}</td>
                                    <td style="font-weight: 500; color: #333;">{{ $pedido->product_name }}</td>
                                    <td>
                                        {{ $pedido->order->user ? $pedido->order->user->name : 'Cliente Invitado' }}
                                        @if(!$pedido->order->user)
                                            <br><small style="color: #999;">{{ $pedido->order->guest_name ?? 'N/A' }}</small>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $pedido->order->user ? $pedido->order->user->email : $pedido->order->guest_email ?? 'N/A' }}
                                    </td>
                                    <td>
                                        {{ $pedido->order->billing_phone ?? 'N/A' }}
                                    </td>
                                    <td style="text-align: center;">{{ $pedido->quantity }}</td>
                                    <td style="font-weight: 600;">${{ number_format($pedido->unit_price, 0, ',', '.') }}</td>
                                    <td style="font-weight: 700; color: #333;">${{ number_format($pedido->total_price, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $statusLabels = [
                                                'pending' => 'Pendiente',
                                                'paid' => 'Pagado',
                                                'processing' => 'Procesando',
                                                'shipped' => 'Enviado',
                                                'delivered' => 'Entregado',
                                                'cancelled' => 'Cancelado'
                                            ];
                                        @endphp
                                        <span class="status-badge status-{{ $pedido->order->status }}">
                                            {{ $statusLabels[$pedido->order->status] ?? ucfirst($pedido->order->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="pagination-wrapper">
                    {{ $pedidos->appends(request()->query())->links() }}
                </div>
            @else
                <div class="orders-table">
                    <div class="no-orders">
                        <i class="fas fa-shopping-bag"></i>
                        <h3>No hay pedidos</h3>
                        <p>
                            @if(request()->hasAny(['status', 'fecha']))
                                No se encontraron pedidos con los filtros aplicados.
                            @else
                                Aún no tienes pedidos. Los pedidos aparecerán aquí cuando los clientes compren tus productos.
                            @endif
                        </p>
                        @if(request()->hasAny(['status', 'fecha']))
                            <a href="{{ route('empresa.pedidos') }}" class="btn btn-primary" style="margin-top: 15px;">
                                <i class="fas fa-times"></i>
                                Limpiar Filtros
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('components.footer')
</body>
</html>