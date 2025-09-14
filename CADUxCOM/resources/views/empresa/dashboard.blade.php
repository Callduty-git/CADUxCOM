<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Empresa - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; }
        
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
        
        .dashboard-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            margin: 0;
            overflow: hidden;
            min-height: calc(100vh - 200px);
        }
        
        .dashboard-header {
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .welcome-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .welcome-subtitle {
            font-size: 1.2rem;
            opacity: 0.9;
        }
        
        .dashboard-content {
            padding: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            border: 2px solid #89CF6D;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #89CF6D 0%, #AA5FC7 100%);
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
            color: #49874E;
        }
        
        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #000000;
            margin-bottom: 8px;
        }
        
        .stat-label {
            font-size: 1rem;
            color: #49874E;
            font-weight: 600;
        }
        
        .info-section {
            background: linear-gradient(135deg, #FFFFFF 0%, #D994F4 100%);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            border: 2px solid #89CF6D;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #49874E;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        
        .info-item {
            background: white;
            padding: 20px;
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
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .btn-action {
            padding: 15px 25px;
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
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(137, 207, 109, 0.3);
        }
        
        .btn-secondary {
            background: #AA5FC7;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #8B4A9F;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(170, 95, 199, 0.3);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #ed1313 0%, #B71C1C 100%);
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(237, 19, 19, 0.3);
        }
        
        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .no-logo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
        }
        
        .quick-actions {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .quick-actions h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .quick-actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .quick-action-btn {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 15px;
            border-radius: 10px;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .quick-action-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }
        
        .quick-action-icon {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
        }
        
        .quick-action-text {
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        @media (max-width: 768px) {
            .dashboard-content {
                padding: 20px;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .stat-card {
                padding: 15px;
            }
            
            .stat-icon {
                font-size: 1.5rem;
            }
            
            .stat-value {
                font-size: 1.5rem;
            }
            
            .stat-label {
                font-size: 0.9rem;
            }
            
            .info-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
            
            .quick-actions-grid {
                grid-template-columns: 1fr;
            }
        }
        
        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* ====== MODAL ESTILOS ====== */
        .modal { 
            display: none; 
            position: fixed; 
            z-index: 2000; 
            left: 0; 
            top: 0; 
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
        }
        .modal-content { 
            background-color: #fff; 
            margin: 5% auto; 
            padding: 30px; 
            border-radius: 20px; 
            width: 90%; 
            max-width: 800px; 
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease-out;
        }
        @keyframes modalSlideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .close { 
            color: #6c757d; 
            font-size: 28px; 
            font-weight: bold; 
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .close:hover { color: #dc3545; }
        .save-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }
        
        /* ====== ESTILOS MODAL BIENVENIDA ====== */
        .modal-bienvenida {
            display: none; /* Oculto por defecto */
            position: fixed;
            z-index: 3000; /* Z-index alto para estar encima de otros modales */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        .modal-visible {
            display: flex; /* Muestra el modal */
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .modal-contenido-bienvenida {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 40px;
            border-radius: 25px;
            text-align: center;
            width: 450px;
            max-width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            border: none;
            position: relative;
            font-family: 'Inter', sans-serif;
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .header-modal-bienvenida {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
        }
        .header-modal-bienvenida .logo {
            width: 60px;
            height: auto;
            margin-right: 15px;
            filter: brightness(0) invert(1);
        }
        .title-modal {
            font-size: 2rem;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .body-modal-bienvenida h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 20px 0 15px 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }
        .body-modal-bienvenida p {
            font-size: 1.1rem;
            font-weight: 400;
            margin: 10px 0;
            opacity: 0.95;
        }
    </style>
</head>
<body>
    
    <!-- NUEVO HEADER -->
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
            <div class="dashboard-container">
                <!-- Header del Dashboard -->
                <div class="dashboard-header">
                    <div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 20px;">
                        @if($empresa->Foto)
                            <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Logo de la empresa" class="company-logo">
                        @else
                            <div class="no-logo">
                                <i class="fas fa-building"></i>
                            </div>
                        @endif
                        <div>
                            <h1 class="welcome-title">¡Bienvenida, {{ $empresa->Nombre }}!</h1>
                            <p class="welcome-subtitle">Gestiona tu negocio desde tu panel de control</p>
                        </div>
                    </div>
                </div>

                <div class="dashboard-content">
                    <!-- Estadísticas Rápidas -->
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-boxes stat-icon"></i>
                            <div class="stat-value">{{ $productos->count() }}</div>
                            <div class="stat-label">Total Productos</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-check-circle stat-icon"></i>
                            <div class="stat-value">{{ $productos->where('Cantidad', '>', 0)->count() }}</div>
                            <div class="stat-label">Productos Disponibles</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-exclamation-triangle stat-icon"></i>
                            <div class="stat-value">{{ $productos->where('Cantidad', 0)->count() }}</div>
                            <div class="stat-label">Productos Agotados</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-dollar-sign stat-icon"></i>
                            <div class="stat-value">${{ number_format($productos->sum('Precio'), 0, ',', '.') }}</div>
                            <div class="stat-label">Valor Total de Productos</div>
                        </div>
                    </div>

                    <!-- Acciones Rápidas -->
                    <div class="quick-actions">
                        <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                        <div class="quick-actions-grid">
                            <a href="{{ route('productos.create') }}" class="quick-action-btn">
                                <i class="fas fa-plus quick-action-icon"></i>
                                <span class="quick-action-text">Crear Producto</span>
                            </a>
                            <a href="{{ route('empresa.password.change') }}" class="quick-action-btn">
                                <i class="fas fa-key quick-action-icon"></i>
                                <span class="quick-action-text">Cambiar Contraseña</span>
                            </a>
                        </div>
                    </div>

                    <!-- Información de la Empresa -->
                    <div class="info-section">
                        <h3 class="section-title">
                            <i class="fas fa-building"></i>
                            Información de la Empresa
                        </h3>
                        
                        <!-- Foto y Certificado -->
                        <div style="display: flex; gap: 30px; margin-bottom: 25px; justify-content: center;">
                            <div style="text-align: center;">
                                <h4 style="margin-bottom: 15px; color: #495057; font-size: 1.1rem;">Logo de la Empresa</h4>
                                @if($empresa->Foto)
                                    <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Logo de la empresa" style="width: 180px; height: 180px; border-radius: 20px; object-fit: cover; border: 4px solid #e9ecef; box-shadow: 0 6px 20px rgba(0,0,0,0.15);">
                                @else
                                    <div style="width: 180px; height: 180px; border-radius: 20px; background: #f8f9fa; border: 4px solid #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 3rem;">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div style="text-align: center;">
                                <h4 style="margin-bottom: 15px; color: #495057; font-size: 1.1rem;">Certificado</h4>
                        @if($empresa->Certificado_Camara_de_comercio)
                            @php
                                $certPath = asset('storage/' . $empresa->Certificado_Camara_de_comercio);
                                $ext = strtolower(pathinfo($empresa->Certificado_Camara_de_comercio, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $certPath }}" alt="Certificado" style="width: 180px; height: 180px; border-radius: 20px; object-fit: cover; border: 4px solid #e9ecef; box-shadow: 0 6px 20px rgba(0,0,0,0.15); cursor: pointer;" onclick="window.open('{{ $certPath }}', '_blank')">
                            @else
                                        <div style="width: 180px; height: 180px; border-radius: 20px; background: #f8f9fa; border: 4px solid #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 3rem; cursor: pointer;" onclick="window.open('{{ $certPath }}', '_blank')">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                            @endif
                        @else
                                    <div style="width: 180px; height: 180px; border-radius: 20px; background: #f8f9fa; border: 4px solid #e9ecef; display: flex; align-items: center; justify-content: center; color: #6c757d; font-size: 3rem;">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                        @endif
                            </div>
                        </div>

                        <!-- Información Básica -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Nombre</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->Nombre }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Correo</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->email }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Teléfono</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->Contacto }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">NIT</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->NIT }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Dirección</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->Direccion }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Ubicación</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->Ubicacion }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Municipio</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->Municipio }}</div>
                            </div>
                            <div style="background: white; padding: 15px; border-radius: 8px; border: 1px solid #e9ecef;">
                                <div style="font-weight: 600; color: #6c757d; font-size: 0.85rem; margin-bottom: 5px;">Registro</div>
                                <div style="color: #495057; font-size: 1rem;">{{ $empresa->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div class="action-buttons">
                        <button id="openModal" class="btn-action btn-warning">
                            <i class="fas fa-edit"></i>
                            Editar Perfil
                        </button>
                        <a href="{{ route('empresa.password.change') }}" class="btn-action btn-secondary">
                            <i class="fas fa-key"></i>
                            Cambiar Contraseña
                        </a>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #495057; font-size: 1.5rem; font-weight: 600;">
                    <i class="fas fa-edit"></i> Editar Perfil
                </h3>
                <span class="close" id="closeModal" style="font-size: 28px; cursor: pointer; color: #6c757d;">&times;</span>
            </div>
            <form id="editProfileForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Nombre de la Empresa</label>
                        <input type="text" name="Nombre" value="{{ $empresa->Nombre }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Correo Electrónico</label>
                        <input type="email" name="email" value="{{ $empresa->email }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Dirección</label>
                        <input type="text" name="Direccion" value="{{ $empresa->Direccion }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Teléfono de Contacto</label>
                        <input type="text" name="Contacto" value="{{ $empresa->Contacto }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">NIT</label>
                        <input type="text" name="NIT" value="{{ $empresa->NIT }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Ubicación</label>
                        <input type="text" name="Ubicacion" value="{{ $empresa->Ubicacion }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Municipio</label>
                    <input type="text" name="Municipio" value="{{ $empresa->Municipio }}" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Logo de la Empresa</label>
                        <input type="file" name="Foto" accept="image/*" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                    <div>
                        <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057;">Certificado Cámara de Comercio</label>
                        <input type="file" name="Certificado_Camara_de_comercio" style="width: 100%; padding: 12px; border: 2px solid #e9ecef; border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;" onfocus="this.style.borderColor='#28a745'" onblur="this.style.borderColor='#e9ecef'">
                    </div>
                </div>
                <div style="text-align: center;">
                    <button type="submit" class="save-btn" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); color: white; border: none; padding: 15px 30px; border-radius: 10px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);">
                        <i class="fas fa-save"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="modal-bienvenida" class="modal-bienvenida">
        <div class="modal-contenido-bienvenida">
            <div class="header-modal-bienvenida">
                <img src="{{ asset('images/logo-caduxcom.png') }}" alt="Logo" class="logo">
                <h2 class="title-modal">CADUxCOM</h2>
            </div>
            <div class="body-modal-bienvenida">
                <h3 id="welcome-message">¡Bienvenida, {{ $empresa->Nombre }}!</h3>
                <p>Nos alegra tenerte de nuevo en tu panel de control.</p>
                <p style="margin-top: 15px; font-size: 0.9rem; opacity: 0.8;">Gestiona tus productos y mantén tu inventario actualizado.</p>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Código para el modal de editar perfil
        document.getElementById('openModal').addEventListener('click', function(){
            document.getElementById('editModal').style.display = 'block';
        });
        document.getElementById('closeModal').addEventListener('click', function(){
            document.getElementById('editModal').style.display = 'none';
        });
        window.onclick = function(event) {
            if (event.target == document.getElementById('editModal')) {
                document.getElementById('editModal').style.display = 'none';
            }
        };
        document.getElementById('editProfileForm').addEventListener('submit', function(e){
            e.preventDefault();
            let formData = new FormData(this);
            fetch("{{ route('empresa.perfil.update') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'PUT'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) throw new Error("Error en la actualización");
                return response.json();
            })
            .then(data => {
                showNotification("Perfil actualizado correctamente ✅", "success", "Éxito");
                setTimeout(() => location.reload(), 1500);
            })
            .catch(error => {
                console.error(error);
                showNotification("Hubo un problema al actualizar el perfil ❌", "error", "Error");
            });
        });

        // Modal de bienvenida automático
            const modal = document.getElementById('modal-bienvenida');
            const welcomeMessage = document.getElementById('welcome-message');
            const empresaNombre = "{{ $empresa->Nombre }}";
            
        // Verificar si ya se mostró el modal en esta sesión
        const welcomeShown = sessionStorage.getItem('welcomeShown');
        
        if (!welcomeShown) {
            // Actualiza el mensaje con el nombre de la empresa
            welcomeMessage.textContent = `¡Bienvenida, ${empresaNombre}!`;

            // Muestra el modal después de un pequeño delay
            setTimeout(function() {
            modal.classList.add('modal-visible');
            }, 500);

            // Marca como mostrado en esta sesión
            sessionStorage.setItem('welcomeShown', 'true');

            // Oculta el modal después de 5 segundos
            setTimeout(function() {
                modal.classList.remove('modal-visible');
            }, 5000); // 5000 milisegundos = 5 segundos
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
    });
    </script>

    <!-- Footer -->
    <x-footer />
    
    <!-- Scripts -->
    <script src="{{ asset('js/notifications.js') }}"></script>
</body>
</html>