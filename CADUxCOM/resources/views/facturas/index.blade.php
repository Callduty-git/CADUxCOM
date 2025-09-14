<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Log de Productos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
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
        
        /* Estilos para el log de productos */
        .log-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 0;
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }
        
        .log-header {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: white;
            padding: 25px 30px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .log-title {
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .log-counter-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 1.5rem 0;
            padding: 1.5rem;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            border: 2px solid #89CF6D;
            box-shadow: 0 8px 25px rgba(137, 207, 109, 0.2);
        }
        
        .log-counter-large {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 3rem;
            font-weight: 800;
            color: #2d3748;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .counter-number {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            min-width: 80px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(137, 207, 109, 0.4);
            border: 3px solid #ffffff;
        }
        
        .counter-separator {
            font-size: 2.5rem;
            color: #89CF6D;
            font-weight: 900;
        }
        
        .counter-total {
            background: linear-gradient(135deg, #49874E 0%, #2d5a32 100%);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 15px;
            min-width: 80px;
            text-align: center;
            box-shadow: 0 6px 20px rgba(73, 135, 78, 0.4);
            border: 3px solid #ffffff;
        }
        
        .counter-label {
            margin-top: 0.5rem;
            font-size: 1.1rem;
            font-weight: 600;
            color: #89CF6D;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        /* Estilos de advertencia cuando está cerca del límite */
        .counter-warning {
            border-color: #ed1313 !important;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%) !important;
            animation: pulse-warning 2s infinite;
        }
        
        .counter-number-warning {
            background: linear-gradient(135deg, #ed1313 0%, #dc2626 100%) !important;
            box-shadow: 0 6px 20px rgba(237, 19, 19, 0.4) !important;
        }
        
        .counter-warning .counter-label {
            color: #ed1313 !important;
        }
        
        @keyframes pulse-warning {
            0% {
                box-shadow: 0 8px 25px rgba(237, 19, 19, 0.2);
            }
            50% {
                box-shadow: 0 8px 25px rgba(237, 19, 19, 0.4);
            }
            100% {
                box-shadow: 0 8px 25px rgba(237, 19, 19, 0.2);
            }
        }
        
        .log-actions {
            margin-top: 1rem;
            display: flex;
            justify-content: flex-end;
        }
        
        .btn-clear-logs {
            background: linear-gradient(135deg, #ed1313 0%, #dc2626 100%);
            color: white;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(237, 19, 19, 0.3);
        }
        
        .btn-clear-logs:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(237, 19, 19, 0.4);
        }
        
        /* Mensajes de sesión */
        .session-message {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            animation: slideInDown 0.5s ease-out;
        }
        
        .session-message.success {
            border-left: 4px solid #89CF6D;
            background: linear-gradient(135deg, #f0fff4 0%, #dcfce7 100%);
        }
        
        .session-message.error {
            border-left: 4px solid #ed1313;
            background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
        }
        
        .notification-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 14px;
        }
        
        .session-message.success .notification-icon {
            background: #89CF6D;
            color: white;
        }
        
        .session-message.error .notification-icon {
            background: #ed1313;
            color: white;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-message {
            font-weight: 500;
            color: #2d3748;
        }
        
        .notification-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #64748b;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.2s ease;
        }
        
        .notification-close:hover {
            background: rgba(0, 0, 0, 0.1);
            color: #2d3748;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .log-icon {
            font-size: 2rem;
        }
        
        .log-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            margin-top: 5px;
        }
        
        .log-content {
            padding: 30px;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .log-item {
            display: flex;
            align-items: center;
            padding: 20px;
            margin-bottom: 15px;
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
            border-radius: 15px;
            border-left: 5px solid #89CF6D;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .log-separator {
            background: linear-gradient(135deg, #AA5FC7 0%, #49874E 100%);
            color: white;
            padding: 15px 25px;
            margin: 25px 0 15px 0;
            border-radius: 25px;
            font-size: 1.1rem;
            font-weight: 700;
            text-align: center;
            box-shadow: 0 4px 15px rgba(170, 95, 199, 0.3);
            position: relative;
        }
        
        .log-separator::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            opacity: 0.3;
        }
        
        .log-separator::after {
            content: '';
            position: absolute;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            opacity: 0.3;
        }
        
        .log-item:hover {
            transform: translateX(8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }
        
        .log-item.agregado {
            border-left-color: #89CF6D;
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
        }
        
        .log-item.eliminado {
            border-left-color: #ed1313;
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
        }
        
        .log-item.modificado {
            border-left-color: #AA5FC7;
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
        }
        
        .log-item.default {
            border-left-color: #89CF6D;
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
        }
        
        .log-icon-container {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.3rem;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .log-icon-container.agregado {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
        }
        
        .log-icon-container.eliminado {
            background: linear-gradient(135deg, #ed1313 0%, #B71C1C 100%);
        }
        
        .log-icon-container.modificado {
            background: linear-gradient(135deg, #AA5FC7 0%, #8B4A9F 100%);
        }
        
        .log-icon-container.default {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
        }
        
        .product-image {
            width: 80px;
            height: 80px;
            border-radius: 15px;
            object-fit: cover;
            border: 3px solid #e9ecef;
            margin-right: 20px;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .product-image.agregado {
            border-color: #28a745;
        }
        
        .product-image.eliminado {
            border-color: #dc3545;
        }
        
        .product-image.modificado {
            border-color: #ffc107;
        }
        
        .product-image.default {
            border-color: #adb5bd;
        }
        
        .log-details {
            flex: 1;
        }
        
        .log-accion {
            font-size: 1.1rem;
            font-weight: 700;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .log-descripcion {
            color: #6c757d;
            font-size: 1rem;
            margin-bottom: 8px;
            line-height: 1.4;
        }
        
        .log-fecha {
            color: #adb5bd;
            font-size: 0.9rem;
            font-weight: 500;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }
        
        .empty-icon {
            font-size: 4rem;
            color: #dee2e6;
            margin-bottom: 20px;
        }
        
        .empty-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        
        .empty-subtitle {
            font-size: 1rem;
            opacity: 0.8;
        }
        
        /* Scrollbar personalizado */
        .log-content::-webkit-scrollbar {
            width: 8px;
        }
        
        .log-content::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .log-content::-webkit-scrollbar-thumb {
            background: #adb5bd;
            border-radius: 10px;
        }
        
        .log-content::-webkit-scrollbar-thumb:hover {
            background: #6c757d;
        }
        
        /* Estilos profesionales para la barra de búsqueda */
        .search-container {
            margin-left: auto;
            min-width: 400px;
            position: relative;
        }
        
        .search-form {
            width: 100%;
        }
        


        
        .search-input-group {
            position: relative;
            display: flex;
            align-items: center;
            background: transparent;
            border-radius: 12px;
            padding: 12px 4px 12px 16px;
            border: none;
            outline: none;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .search-input-group:focus-within {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .search-icon {
            color: #64748b;
            margin-right: 16px;
            font-size: 1.2rem;
            opacity: 0.7;
            transition: all 0.3s ease;
        }
        
        .search-input-group:focus-within .search-icon {
            color: #28a745;
            opacity: 1;
        }
        
        .search-input {
            flex: 1;
            background: transparent;
            border: none;
            color: #1e293b;
            font-size: 1rem;
            padding: 4px 12px 4px 0;
            outline: none;
            font-weight: 500;
            letter-spacing: 0.01em;
            min-width: 0;
        }
        
        .search-input::placeholder {
            color: rgb(9, 81, 183);
            font-weight: 400;
            letter-spacing: 0;
        }
        
        .search-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-left: auto;
            justify-content: flex-end;
        }
        
        .search-button {
            background: #28a745;
            border: none;
            color: white;
            padding: 10px 12px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
        }
        
        .search-button:hover {
            background: #218838;
            transform: scale(1.05);
        }
        
        .clear-search {
            color: #94a3b8;
            text-decoration: none;
            padding: 10px;
            border-radius: 10px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: rgba(148, 163, 184, 0.1);
        }
        
        .clear-search:hover {
            color: #ef4444;
            background: rgba(239, 68, 68, 0.1);
            transform: scale(1.05);
        }
        
        /* Sugerencias de búsqueda profesionales */
        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 
                0 20px 60px rgba(0, 0, 0, 0.12),
                0 8px 24px rgba(0, 0, 0, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.9);
            margin-top: 12px;
            padding: 24px;
            z-index: 1000;
            display: none;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .search-suggestions.show {
            display: block;
            animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .suggestion-category {
            margin-bottom: 20px;
        }
        
        .suggestion-category:last-child {
            margin-bottom: 0;
        }
        
        .category-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 12px;
            display: block;
            position: relative;
        }
        
        .category-title::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 24px;
            height: 2px;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 1px;
        }
        
        .suggestion-items {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        
        .suggestion-item {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            color: #475569;
            padding: 8px 16px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
            position: relative;
            overflow: hidden;
        }
        
        .suggestion-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.1), transparent);
            transition: left 0.5s;
        }
        
        .suggestion-item:hover::before {
            left: 100%;
        }
        
        .suggestion-item:hover {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 
                0 8px 20px rgba(16, 185, 129, 0.3),
                0 4px 8px rgba(16, 185, 129, 0.2);
            border-color: transparent;
        }
        
        /* Estilos para el contador de resultados */
        .search-results-info {
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            color: #1976d2;
            padding: 12px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 0.95rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
            border-left: 4px solid #2196f3;
        }
        
        .search-results-info i {
            font-size: 1.1rem;
        }
        
        /* Responsive para la barra de búsqueda */
        @media (max-width: 768px) {
            .search-container {
                min-width: 100%;
                margin-left: 0;
                margin-top: 15px;
            }
            
            .log-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
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
                <a href="{{ route('empresa.dashboard') }}" class="btn">Inicio</a>
                <a href="{{ route('empresa.productos.index') }}" class="btn">Productos</a>
                <a href="{{ route('empresa.facturas') }}" class="btn">Log de Productos</a>
                <form method="POST" action="{{ route('empresa.logout') }}" style="margin-top: 10px;">
                    @csrf
                    <button type="submit" class="btn" aria-label="Cerrar sesión">Salir</button>
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
            
            <div class="log-container">
                <div class="log-header">
                    <div>
                        <div class="log-title">
                            <i class="fas fa-clipboard-list log-icon"></i>
                            Log de Productos
                        </div>
                        <div class="log-subtitle">Registro de actividades de productos - Subidas y eliminaciones</div>
                    </div>
                    
                    <!-- Contador de logs más visible -->
                    <div class="log-counter-container {{ $totalLogs >= 45 ? 'counter-warning' : '' }}">
                        <div class="log-counter-large">
                            <span class="counter-number {{ $totalLogs >= 45 ? 'counter-number-warning' : '' }}">{{ $totalLogs }}</span>
                            <span class="counter-separator">/</span>
                            <span class="counter-total">{{ $maxLogs }}</span>
                        </div>
                        <div class="counter-label">
                            @if($totalLogs >= 45)
                                ⚠️ Registros - Cerca del Límite
                            @else
                                Registros
                            @endif
                        </div>
                    </div>
                    
                    <!-- Botón de limpiar logs cuando esté cerca del límite -->
                    @if($totalLogs >= 45)
                        <div class="log-actions">
                            <form method="POST" action="{{ route('empresa.facturas.clear-logs') }}" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que quieres eliminar todos los logs? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-clear-logs">
                                    <i class="fas fa-trash-alt"></i>
                                    Limpiar Todos los Logs
                                </button>
                            </form>
                        </div>
                    @endif
                    
                    <!-- Barra de búsqueda mejorada -->
                    <div class="search-container">
                        <form method="GET" action="{{ route('empresa.facturas') }}" class="search-form">
                            <div class="search-input-group">
                                <div class="search-icon">
                                    <i class="fas fa-search"></i>
                                </div>
                                <input type="text" 
                                       name="search" 
                                       placeholder="Buscar por producto, acción, fecha (ayer, hoy, semana)..." 
                                       class="search-input" 
                                       value="{{ request('search') }}"
                                       autocomplete="off"
                                       id="smartSearchInput">
                                <div class="search-actions">
                                    @if(request('search'))
                                        <a href="{{ route('empresa.facturas') }}" class="clear-search" title="Limpiar búsqueda">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    @endif
                                    <button type="submit" class="search-button" title="Buscar">
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Sugerencias de búsqueda -->
                            <div class="search-suggestions" id="searchSuggestions">
                                <div class="suggestion-category">
                                    <span class="category-title">📅 Búsquedas por fecha:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="hoy">hoy</span>
                                        <span class="suggestion-item" data-search="ayer">ayer</span>
                                        <span class="suggestion-item" data-search="esta semana">esta semana</span>
                                        <span class="suggestion-item" data-search="este mes">este mes</span>
                                    </div>
                                </div>
                                <div class="suggestion-category">
                                    <span class="category-title">📆 Días de la semana:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="lunes">lunes</span>
                                        <span class="suggestion-item" data-search="martes">martes</span>
                                        <span class="suggestion-item" data-search="miércoles">miércoles</span>
                                        <span class="suggestion-item" data-search="jueves">jueves</span>
                                        <span class="suggestion-item" data-search="viernes">viernes</span>
                                        <span class="suggestion-item" data-search="sábado">sábado</span>
                                        <span class="suggestion-item" data-search="domingo">domingo</span>
                                    </div>
                                </div>
                                <div class="suggestion-category">
                                    <span class="category-title">⚡ Acciones:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="agregar">agregar</span>
                                        <span class="suggestion-item" data-search="eliminar">eliminar</span>
                                    </div>
                                </div>
                                <div class="suggestion-category">
                                    <span class="category-title">💡 Ejemplos de fechas:</span>
                                    <div class="suggestion-items">
                                        <span class="suggestion-item" data-search="{{ date('d/m/Y') }}">{{ date('d/m/Y') }}</span>
                                        <span class="suggestion-item" data-search="{{ date('d/m/Y', strtotime('-1 day')) }}">{{ date('d/m/Y', strtotime('-1 day')) }}</span>
                                        <span class="suggestion-item" data-search="{{ date('d/m/Y', strtotime('-7 days')) }}">{{ date('d/m/Y', strtotime('-7 days')) }}</span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="log-content">
                    @if(request('search') && count($logs) > 0)
                        <div class="search-results-info">
                            <i class="fas fa-info-circle"></i>
                            Se encontraron {{ count(array_filter($logs, function($log) { return $log['type'] === 'log'; })) }} resultado(s) para "{{ request('search') }}"
                        </div>
                    @endif
                    
                    @forelse ($logs as $log)
                        @if($log['type'] === 'separator')
                            <div class="log-separator">{{ $log['text'] }}</div>
                        @else
                            @php
                                $logData = $log['data'];
                                $accion = strtolower($logData->accion ?? '');
                                $tipoClase = 'default';
                                $icono = 'fas fa-cog';
                                
                                if (strpos($accion, 'agregar') !== false || strpos($accion, 'crear') !== false || strpos($accion, 'subir') !== false) {
                                    $tipoClase = 'agregado';
                                    $icono = 'fas fa-plus-circle';
                                } elseif (strpos($accion, 'eliminar') !== false || strpos($accion, 'borrar') !== false || strpos($accion, 'delete') !== false) {
                                    $tipoClase = 'eliminado';
                                    $icono = 'fas fa-trash-alt';
                                } elseif (strpos($accion, 'modificar') !== false || strpos($accion, 'editar') !== false || strpos($accion, 'update') !== false) {
                                    $tipoClase = 'modificado';
                                    $icono = 'fas fa-edit';
                                }
                                
                                // Obtener la fecha del log
                                $fechaLog = \Carbon\Carbon::parse($logData->hora);
                            
                            
                            // Intentar extraer información del producto de la descripción
                            $productoImagen = null;
                            $productoNombre = null;
                            
                                // Buscar patrones comunes en la descripción para extraer info del producto
                                if (preg_match('/producto[:\s]+([^,]+)/i', $logData->descripcion ?? '', $matches)) {
                                    $productoNombre = trim($matches[1]);
                                }
                                
                                // Si no encontramos nombre, usar la descripción completa
                                if (!$productoNombre) {
                                    $productoNombre = $logData->descripcion ?? 'Producto';
                                }
                            
                            // Buscar el producto real en la colección de productos
                            $productoReal = null;
                            $imagenProducto = asset('images/icon-congelados.png'); // Imagen por defecto
                            
                            // Buscar por nombre del producto
                            foreach ($productos as $producto) {
                                if (stripos($producto->Nombre, $productoNombre) !== false || 
                                    stripos($productoNombre, $producto->Nombre) !== false) {
                                    $productoReal = $producto;
                                    break;
                                }
                            }
                            
                            // Si encontramos el producto, usar su imagen real
                            if ($productoReal && $productoReal->Foto) {
                                $imagenProducto = asset('storage/' . $productoReal->Foto);
                            } elseif ($productoReal) {
                                // Si el producto existe pero no tiene foto, usar una imagen por defecto según el tipo
                                if ($tipoClase === 'agregado') {
                                    $imagenProducto = asset('images/icon-lacteos.png');
                                } elseif ($tipoClase === 'eliminado') {
                                    $imagenProducto = asset('images/icon-enlatados.png');
                                } elseif ($tipoClase === 'modificado') {
                                    $imagenProducto = asset('images/icon-granos.png');
                                }
                            }
                        @endphp
                        
                        
                        <div class="log-item {{ $tipoClase }}">
                            <!-- Imagen del producto -->
                            <img src="{{ $imagenProducto }}" 
                                 alt="{{ $productoNombre }}" 
                                 class="product-image {{ $tipoClase }}"
                                 onerror="this.src='{{ asset('images/icon-congelados.png') }}'">
                            
                                <div class="log-details">
                                    <div class="log-accion">{{ $logData->accion ?? 'Actividad' }}</div>
                                    <div class="log-descripcion">{{ $logData->descripcion ?? 'Sin descripción' }}</div>
                                    <div class="log-fecha">
                                        <i class="fas fa-clock"></i>
                                        {{ $fechaLog->format('d/m/Y H:i:s') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="empty-state">
                            <div class="empty-icon">
                                @if(request('search'))
                                    <i class="fas fa-search"></i>
                                @else
                                    <i class="fas fa-clipboard-list"></i>
                                @endif
                            </div>
                            <div class="empty-title">
                                @if(request('search'))
                                    No se encontraron resultados
                                @else
                                    No hay actividades registradas
                                @endif
                            </div>
                            <div class="empty-subtitle">
                                @if(request('search'))
                                    No se encontraron actividades que coincidan con "{{ request('search') }}"
                                @else
                                    Las actividades de productos aparecerán aquí cuando subas o elimines productos
                                @endif
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('smartSearchInput');
            const searchSuggestions = document.getElementById('searchSuggestions');
            const suggestionItems = document.querySelectorAll('.suggestion-item');
            
            // Mostrar/ocultar sugerencias
            searchInput.addEventListener('focus', function() {
                searchSuggestions.classList.add('show');
            });
            
            searchInput.addEventListener('blur', function() {
                // Delay para permitir clicks en sugerencias
                setTimeout(() => {
                    searchSuggestions.classList.remove('show');
                }, 200);
            });
            
            // Click en sugerencias
            suggestionItems.forEach(item => {
                item.addEventListener('click', function() {
                    const searchTerm = this.getAttribute('data-search');
                    searchInput.value = searchTerm;
                    searchSuggestions.classList.remove('show');
                    // Enviar formulario automáticamente
                    searchInput.closest('form').submit();
                });
            });
            
            // Búsqueda inteligente con Enter
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.closest('form').submit();
                }
            });
            
            // Auto-completado inteligente
            searchInput.addEventListener('input', function() {
                const value = this.value.toLowerCase();
                
                // Mostrar sugerencias relevantes basadas en lo que se está escribiendo
                suggestionItems.forEach(item => {
                    const itemText = item.textContent.toLowerCase();
                    if (itemText.includes(value) || value.includes(itemText)) {
                        item.style.display = 'inline-block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
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

    <!-- Footer -->
    <x-footer />
</body>
</html>
