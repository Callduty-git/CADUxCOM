<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/empresa-sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-empresa.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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

        /* ====== SIDEBAR CONTAINER ====== */


        .sidebar {
            width: 100px;
            padding: 0; /* que el alto dependa de los botones */
            display: flex;
            flex-direction: column;
            align-items: center;
            border-radius: 0;
            border: none;
            position: relative;
            z-index: 1001;
            box-shadow: none;
            transition: all 0.3s ease;
            opacity: 0.95;
            overflow: visible; /* que crezca con el contenido */
            max-height: none; /* sin tope de altura */
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
            padding: 8px 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            gap: 15px;
            align-items: center;
        }

        .nav-buttons .btn {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            gap: 15px;
            background-color: #d88ef0;
            color: white;
            padding: 10px 18px;
            text-align: left;
            border-radius: 15px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-size: 14px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            width: 60px;
            min-width: 60px;
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

        /* Estilos para el badge de notificaciones */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 11px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 10;
        }

        .sidebar:hover .notification-badge {
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            position: absolute;
        }
        
        .dashboard-panel {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
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
            background-color: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            border-radius: 18px;
            width: 92%;
            max-width: 840px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
            animation: modalSlideIn 0.3s ease-out;
            border: 1px solid #e9ecef;
            overflow: hidden;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 24px;
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            color: #fff;
        }
        .modal-title {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-body {
            padding: 24px;
            background: #fff;
        }
        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .close {
            color: rgba(255,255,255,0.9);
            font-size: 26px;
            font-weight: bold;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }
        .close:hover { opacity: 0.8; }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 18px;
        }
        .form-row { margin-bottom: 18px; }
        .form-field label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #495057;
            font-size: 0.95rem;
        }
        .input-control {
            width: 100%;
            padding: 12px 12px;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }
        .input-control:focus {
            border-color: #49874E;
            outline: none;
            box-shadow: 0 0 0 4px rgba(73, 135, 78, 0.12);
        }
        .modal-actions { text-align: center; margin-top: 6px; }
        .save-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 14px 28px;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 6px 18px rgba(40, 167, 69, 0.25);
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }
        .save-btn:hover { transform: translateY(-1px); }
        .logo-preview {
            width: 100%;
            max-width: 160px;
            height: 160px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #e9ecef;
            background: #f8f9fa;
        }
        @media (max-width: 768px) { .form-grid { grid-template-columns: 1fr; } }
        .save-btn:hover { box-shadow: 0 10px 25px rgba(40, 167, 69, 0.35); }

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
            background-color: rgba(0, 0, 0, 0.7);
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }
        .modal-visible {
            display: flex;
            animation: fadeIn 0.5s ease-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
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

        /* ====== SANDBOX BANNER ====== */
        .sandbox-banner {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 10px;
            text-align: center;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(255, 107, 107, 0.3);
            border: 2px solid rgba(255, 255, 255, 0.2);
            animation: pulse-banner 2s infinite;
        }

        @keyframes pulse-banner {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

    </style>
</head>
<body>
    
    <x-header-empresa />
    <x-empresa-sidebar />
    <div class="main-container">
        <main class="dashboard-panel">
            @php $empresa = Auth::guard('empresa')->user(); @endphp
            @if($empresa && strtolower($empresa->status ?? '') === 'sandbox')
                <div class="sandbox-banner">
                    <strong>Modo Sandbox:</strong>
                    Estás operando en modo de pruebas. Validaremos tus datos antes de pasar a producción.
                </div>
            @endif
            <div class="dashboard-container">
                <div class="dashboard-header">
                    <div class="flex items-center justify-center gap-5 mb-5">
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
                    @if(session('success'))
                        <div style="background: #e6fffa; color: #065f46; border: 1px solid #99f6e4; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                            <i class="fas fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div style="background: #fee2e2; color: #7f1d1d; border: 1px solid #fecaca; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                        </div>
                    @endif
                    <div class="stats-grid">
                        <div class="stat-card">
                            <i class="fas fa-boxes stat-icon"></i>
                            <div class="stat-value">{{ $productos->count() }}</div>
                            <div class="stat-label">Total Productos</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-shopping-cart stat-icon"></i>
                            <div class="stat-value">{{ $pedidosPendientes }}</div>
                            <div class="stat-label">Pedidos Pendientes</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-dollar-sign stat-icon"></i>
                            <div class="stat-value">${{ number_format($totalVentas, 0, ',', '.') }}</div>
                            <div class="stat-label">Total Ventas</div>
                        </div>
                        <div class="stat-card">
                            <i class="fas fa-chart-line stat-icon"></i>
                            <div class="stat-value">${{ number_format($ventasHoy, 0, ',', '.') }}</div>
                            <div class="stat-label">Ventas Hoy</div>
                        </div>
                    </div>
                    
                    <!-- Sección de Pedidos Recientes -->
                    <div class="recent-orders-section" style="margin-bottom: 40px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                            <h3 style="color: #333; font-size: 1.5rem; font-weight: 600; margin: 0;">
                                <i class="fas fa-shopping-bag" style="color: #89CF6D; margin-right: 10px;"></i>
                                Pedidos Recientes
                            </h3>
                            <a href="{{ route('empresa.pedidos') }}" class="btn btn-primary" style="background: #89CF6D; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 500;">
                                Ver Todos los Pedidos
                            </a>
                        </div>
                        
                        @if($pedidos->count() > 0)
                            <div style="background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); overflow: hidden;">
                                <table style="width: 100%; border-collapse: collapse;">
                                    <thead style="background: #f8f9fa;">
                                        <tr>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Pedido #</th>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Producto</th>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Cliente</th>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Cantidad</th>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Total</th>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Estado</th>
                                            <th style="padding: 15px; text-align: left; font-weight: 600; color: #333;">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pedidos as $pedido)
                                            <tr style="border-bottom: 1px solid #eee;">
                                                <td style="padding: 15px; color: #666;">#{{ $pedido->order->id }}</td>
                                                <td style="padding: 15px; color: #333; font-weight: 500;">{{ $pedido->product_name }}</td>
                                                <td style="padding: 15px; color: #666;">
                                                    {{ $pedido->order->user ? $pedido->order->user->name : 'Cliente Invitado' }}
                                                </td>
                                                <td style="padding: 15px; color: #666;">{{ $pedido->quantity }}</td>
                                                <td style="padding: 15px; color: #333; font-weight: 600;">${{ number_format($pedido->total_price, 0, ',', '.') }}</td>
                                                <td style="padding: 15px;">
                                                    @php
                                                        $statusColors = [
                                                            'pending' => '#fbbf24',
                                                            'paid' => '#10b981',
                                                            'processing' => '#3b82f6',
                                                            'shipped' => '#8b5cf6',
                                                            'delivered' => '#059669',
                                                            'cancelled' => '#ef4444'
                                                        ];
                                                        $statusLabels = [
                                                            'pending' => 'Pendiente',
                                                            'paid' => 'Pagado',
                                                            'processing' => 'Procesando',
                                                            'shipped' => 'Enviado',
                                                            'delivered' => 'Entregado',
                                                            'cancelled' => 'Cancelado'
                                                        ];
                                                    @endphp
                                                    <span style="background: {{ $statusColors[$pedido->order->status] ?? '#6b7280' }}; color: white; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; font-weight: 500;">
                                                        {{ $statusLabels[$pedido->order->status] ?? ucfirst($pedido->order->status) }}
                                                    </span>
                                                </td>
                                                <td style="padding: 15px; color: #666;">{{ $pedido->created_at->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 12px; color: #666;">
                                <i class="fas fa-shopping-bag" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                                <p style="font-size: 1.1rem; margin: 0;">No hay pedidos recientes</p>
                                <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Los pedidos aparecerán aquí cuando los clientes compren tus productos</p>
                            </div>
                        @endif
                    </div>

                    <!-- Notificaciones Recientes -->
                    <div class="recent-orders">
                        <h3><i class="fas fa-bell"></i> Notificaciones Recientes</h3>
                        @if($notificaciones && $notificaciones->count() > 0)
                            <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                                @foreach($notificaciones as $notificacion)
                                    <div style="padding: 15px; border-bottom: 1px solid #eee; {{ !$notificacion->read ? 'background: #f0f9ff;' : '' }}">
                                        <div style="display: flex; justify-content: between; align-items: start;">
                                            <div style="flex: 1;">
                                                <div style="display: flex; align-items: center; margin-bottom: 5px;">
                                                    @php
                                                        $typeIcons = [
                                                            'new_order' => 'fas fa-shopping-cart',
                                                            'order_status_change' => 'fas fa-sync-alt',
                                                            'low_stock' => 'fas fa-exclamation-triangle'
                                                        ];
                                                        $typeColors = [
                                                            'new_order' => '#10b981',
                                                            'order_status_change' => '#3b82f6',
                                                            'low_stock' => '#f59e0b'
                                                        ];
                                                    @endphp
                                                    <i class="{{ $typeIcons[$notificacion->type] ?? 'fas fa-bell' }}" 
                                                       style="color: {{ $typeColors[$notificacion->type] ?? '#6b7280' }}; margin-right: 8px;"></i>
                                                    <h4 style="margin: 0; font-size: 1rem; color: #333;">{{ $notificacion->title }}</h4>
                                                    @if(!$notificacion->read)
                                                        <span style="background: #ef4444; color: white; padding: 2px 6px; border-radius: 10px; font-size: 0.7rem; margin-left: 8px;">Nuevo</span>
                                                    @endif
                                                </div>
                                                <p style="margin: 0; color: #666; font-size: 0.9rem;">{{ $notificacion->message }}</p>
                                                <small style="color: #999; font-size: 0.8rem;">{{ $notificacion->created_at->diffForHumans() }}</small>
                                            </div>
                                            <div style="margin-left: 15px;">
                                                @if(!$notificacion->read)
                                                    <button onclick="markAsRead({{ $notificacion->id }})" 
                                                            style="background: #10b981; color: white; border: none; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; cursor: pointer;">
                                                        Marcar como leída
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div style="text-align: center; margin-top: 15px;">
                                <a href="{{ route('empresa.notifications.index') }}" 
                                   style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                                    Ver todas las notificaciones
                                </a>
                            </div>
                        @else
                            <div style="text-align: center; padding: 40px; background: #f8f9fa; border-radius: 12px; color: #666;">
                                <i class="fas fa-bell-slash" style="font-size: 3rem; color: #ddd; margin-bottom: 15px;"></i>
                                <p style="font-size: 1.1rem; margin: 0;">No hay notificaciones</p>
                                <p style="margin: 5px 0 0 0; font-size: 0.9rem;">Las notificaciones aparecerán aquí cuando ocurran eventos importantes</p>
                            </div>
                        @endif
                    </div>
                    
                    <div class="quick-actions">
                        <h3><i class="fas fa-bolt"></i> Acciones Rápidas</h3>
                            <style>
                            .toggle-btn-clean {
                                border: none;
                                border-radius: 999px;
                                font-weight: 800;
                                color: #fff;
                                cursor: pointer;
                                transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
                                display: inline-flex;
                                align-items: center;
                                position: relative;
                                width: 100px;
                                height: 36px;
                                padding: 0 12px;
                                justify-content: center;
                                letter-spacing: 0.5px;
                                box-shadow: 0 4px 12px rgba(0,0,0,0.12);
                                overflow: hidden;
                            }
                            .toggle-btn-clean.toggle-on { background: #22c55e; }
                            .toggle-btn-clean.toggle-off { background: #ef4444; }
                            .toggle-btn-clean:disabled { opacity: 0.7; cursor: not-allowed; }
                            .toggle-btn-clean::before {
                                content: '';
                                position: absolute;
                                width: 24px;
                                height: 24px;
                                border-radius: 50%;
                                background: #fff;
                                top: 6px;
                                left: 8px;
                                transition: left 0.2s ease;
                                box-shadow: 0 4px 10px rgba(0,0,0,0.18);
                            }
                            .toggle-btn-clean.toggle-on::before { left: 68px; }
                            .toggle-btn-clean .toggle-label { color: #fff; font-weight: 800; }
                            </style>
                        <div class="quick-actions-grid">
                            <a href="{{ route('productos.create') }}" class="quick-action-btn">
                                <i class="fas fa-plus quick-action-icon"></i>
                                <span class="quick-action-text">Crear Producto</span>
                            </a>
                            <a href="{{ route('empresa.password.change') }}" class="quick-action-btn">
                                <i class="fas fa-key quick-action-icon"></i>
                                <span class="quick-action-text">Cambiar Contraseña</span>
                            </a>
                            <a href="#" id="openProgressiveModal" class="quick-action-btn" onclick="return false;">
                                <i class="fas fa-percent quick-action-icon"></i>
                                <span class="quick-action-text">Descuento progresivo</span>
                            </a>
                            <!-- Eliminar productos vencidos -->
                            <form id="deleteExpiredForm" method="POST" action="{{ route('empresa.productos.delete-expired') }}" style="display:none;">
                                @csrf
                            </form>
                            <div class="quick-action-btn" style="display:flex; align-items:center; justify-content:space-between;">
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <i class="fas fa-trash-alt quick-action-icon"></i>
                                    <span class="quick-action-text">Eliminar vencidos</span>
                                </div>
                                <button id="deleteExpiredToggleBtn" type="button" class="toggle-btn-clean toggle-off" onclick="return toggleDeleteExpired(event);" title="Borra productos 1 día después de su fecha de caducidad" aria-pressed="false">
                                    <span class="toggle-label">OFF</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="info-section">
                        <h3 class="section-title">
                            <i class="fas fa-building"></i>
                            Información de la Empresa
                        </h3>
                        
                        <div class="flex gap-8 mb-6 justify-center">
                            <div class="text-center">
                                <h4 class="mb-4 text-gray-700 text-lg">Logo de la Empresa</h4>
                                @if($empresa->Foto)
                                    <img src="{{ asset('storage/' . $empresa->Foto) }}" alt="Logo de la empresa" class="w-48 h-48 rounded-2xl object-cover border-4 border-gray-200 shadow-lg">
                                @else
                                    <div class="w-48 h-48 rounded-2xl bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-gray-600 text-5xl">
                                        <i class="fas fa-building"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="text-center">
                                <h4 class="mb-4 text-gray-700 text-lg">Certificado</h4>
                        @if($empresa->Certificado_Camara_de_comercio)
                            @php
                                $certPath = asset('storage/' . $empresa->Certificado_Camara_de_comercio);
                                $ext = strtolower(pathinfo($empresa->Certificado_Camara_de_comercio, PATHINFO_EXTENSION));
                            @endphp
                            @if(in_array($ext, ['jpg', 'jpeg', 'png']))
                                        <img src="{{ $certPath }}" alt="Certificado" class="w-48 h-48 rounded-2xl object-cover border-4 border-gray-200 shadow-lg cursor-pointer" onclick="window.open('{{ $certPath }}', '_blank')">
                            @else
                                        <div class="w-48 h-48 rounded-2xl bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-gray-600 text-5xl cursor-pointer" onclick="window.open('{{ $certPath }}', '_blank')">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                            @endif
                        @else
                                    <div class="w-48 h-48 rounded-2xl bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-gray-600 text-5xl">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                        @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Nombre</div>
                                <div class="text-gray-700 text-base">{{ $empresa->Nombre }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Correo</div>
                                <div class="text-gray-700 text-base">{{ $empresa->email }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Teléfono</div>
                                <div class="text-gray-700 text-base">{{ $empresa->Contacto }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">NIT</div>
                                <div class="text-gray-700 text-base">{{ $empresa->NIT }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Dirección</div>
                                <div class="text-gray-700 text-base">{{ $empresa->Direccion }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Ubicación</div>
                                <div class="text-gray-700 text-base">{{ $empresa->Ubicacion }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Municipio</div>
                                <div class="text-gray-700 text-base">{{ $empresa->Municipio }}</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg border border-gray-200">
                                <div class="font-semibold text-gray-600 text-sm mb-1">Registro</div>
                                <div class="text-gray-700 text-base">{{ $empresa->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                    </div>
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
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-edit"></i> Editar Perfil
                </h3>
                <span class="close" id="closeModal">&times;</span>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Nombre de la Empresa</label>
                            <input class="input-control" type="text" name="Nombre" value="{{ $empresa->Nombre }}" placeholder="Ej. Panadería La Estrella">
                        </div>
                        <div class="form-field">
                            <label>Correo Electrónico</label>
                            <input class="input-control" type="email" name="email" value="{{ $empresa->email }}" placeholder="empresa@correo.com">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>Dirección</label>
                            <input class="input-control" type="text" name="Direccion" value="{{ $empresa->Direccion }}" placeholder="Calle 123 #45-67">
                        </div>
                        <div class="form-field">
                            <label>Teléfono de Contacto</label>
                            <input class="input-control" type="text" name="Contacto" value="{{ $empresa->Contacto }}" placeholder="300 123 4567">
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-field">
                            <label>NIT</label>
                            <input class="input-control" type="text" name="NIT" value="{{ $empresa->NIT }}" placeholder="900123456-7">
                        </div>
                        <div class="form-field">
                            <label>Ubicación</label>
                            <input class="input-control" type="text" name="Ubicacion" value="{{ $empresa->Ubicacion }}" placeholder="Ciudad / Departamento">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-field">
                            <label>Municipio</label>
                            <input class="input-control" type="text" name="Municipio" value="{{ $empresa->Municipio }}" placeholder="Municipio">
                        </div>
                    </div>
                    <div class="form-grid" style="align-items:center;">
                        <div class="form-field">
                            <label>Logo de la Empresa</label>
                            <input class="input-control" type="file" name="Foto" accept="image/*">
                        </div>
                        <div class="form-field" style="text-align:center;">
                            <img id="logoPreview" class="logo-preview" src="{{ isset($empresa->Foto) && $empresa->Foto ? asset('storage/'.$empresa->Foto) : asset('images/logo-caduxcom.png') }}" alt="Logo">
                        </div>
                    </div>
                    <div class="form-grid" style="align-items:center;">
                        <div class="form-field">
                            <label>Certificado Cámara de Comercio</label>
                            <input class="input-control" type="file" name="Certificado_Camara_de_comercio" accept="application/pdf,image/*">
                        </div>
                        <div class="form-field" style="text-align:center;">
                            @php
                                $certPathModal = isset($empresa->Certificado_Camara_de_comercio) && $empresa->Certificado_Camara_de_comercio
                                    ? asset('storage/' . $empresa->Certificado_Camara_de_comercio)
                                    : null;
                                $certExtModal = $certPathModal
                                    ? strtolower(pathinfo($empresa->Certificado_Camara_de_comercio, PATHINFO_EXTENSION))
                                    : null;
                            @endphp
                            <div id="certPreview" style="display:inline-block;">
                                @if($certPathModal)
                                    @if(in_array($certExtModal, ['jpg', 'jpeg', 'png']))
                                        <img id="certPreviewImg" class="logo-preview" src="{{ $certPathModal }}" alt="Certificado">
                                    @else
                                        <div id="certPreviewIcon" class="w-48 h-48 rounded-2xl bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-gray-600 text-5xl cursor-pointer" onclick="window.open('{{ $certPathModal }}', '_blank')">
                                            <i class="fas fa-file-pdf"></i>
                                        </div>
                                        <div style="margin-top:8px;">
                                            <a href="{{ $certPathModal }}" target="_blank" class="text-blue-600 underline">Ver certificado</a>
                                        </div>
                                    @endif
                                @else
                                    <div class="w-48 h-48 rounded-2xl bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-gray-600 text-5xl">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="submit" class="save-btn">
                            <i class="fas fa-save"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal: Descuento Progresivo -->
    <div id="progressiveDiscountModal" class="modal">
        <div class="modal-content">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h3 style="margin: 0; color: #495057; font-size: 1.5rem; font-weight: 600;">
                    <i class="fas fa-percent"></i> Descuento progresivo
                </h3>
                <span class="close" id="closeProgressiveModal" style="font-size: 28px; cursor: pointer; color: #6c757d;">&times;</span>
            </div>
            <div style="color: #495057; line-height: 1.6; font-size: 0.98rem;">
                <p style="margin-bottom: 10px;">
                    El descuento progresivo reduce automáticamente el precio de tus productos a medida que se acerca su fecha de caducidad. Así fomentas la rotación de inventario y evitas pérdidas por vencimiento.
                </p>
                <div style="background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px; padding: 14px; margin-bottom: 14px;">
                    <div style="font-weight: 600; margin-bottom: 8px; color: #343a40;">Reglas predeterminadas</div>
                    <ul style="margin-left: 18px;">
                        <li>7 días antes de caducar: descuento del 10%.</li>
                        <li>3 días antes de caducar: descuento del 20%.</li>
                        <li>1 día antes de caducar: descuento del 30%.</li>
                    </ul>
                </div>
                <div style="background: #fff; border: 1px dashed #dee2e6; border-radius: 10px; padding: 12px; margin-bottom: 16px;">
                    <div style="font-weight: 600; margin-bottom: 6px; color: #495057;">¿Cómo afecta a tus productos?</div>
                    <ul style="margin-left: 18px;">
                        <li>Aplica a productos con <em>Fecha_Caducidad</em> definida y stock disponible.</li>
                        <li>Se calcula sobre el precio actual y nunca deja el precio en negativo.</li>
                        <li>No aplica si el precio del producto es inferior a $1.000 (valor mínimo por defecto).</li>
                    </ul>
                </div>

                <div style="display: flex; gap: 12px; align-items: center;">
                    <form method="POST" action="{{ route('discount-rules.create-defaults') }}">
                        @csrf
                        <button type="submit" class="btn-action btn-success" style="display: inline-flex; align-items: center; gap: 8px;">
                            <i class="fas fa-play"></i>
                            Activar reglas por defecto
                        </button>
                    </form>
                    <a href="{{ route('discount-rules.discount-rules.index') }}" class="btn-action btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-cogs"></i>
                        Gestionar reglas
                    </a>
                </div>

                <hr style="margin: 16px 0; border: none; border-top: 1px solid #e9ecef;">

                <div style="display: flex; align-items: center; justify-content: space-between; gap: 12px;">
                    <div>
                        <div style="font-weight: 600; color: #495057;">Estado actual</div>
                        <div id="progressiveStatusText" class="{{ $empresa->progressive_discount_enabled ? 'text-success' : 'text-danger' }}" style="font-weight: 600;">
                            {{ $empresa->progressive_discount_enabled ? 'Activo' : 'Inactivo' }}
                        </div>
                    </div>
                    <button id="toggleProgressiveDiscountBtn" class="btn-action {{ $empresa->progressive_discount_enabled ? 'btn-warning' : 'btn-success' }}" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas {{ $empresa->progressive_discount_enabled ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                        {{ $empresa->progressive_discount_enabled ? 'Desactivar' : 'Activar' }} descuento progresivo
                    </button>
                </div>
            </div>
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
        function toggleDeleteExpired(e) {
            e.preventDefault();
            const btn = document.getElementById('deleteExpiredToggleBtn');
            const isOff = btn.classList.contains('toggle-off');
            if (isOff) {
                const confirmed = confirm('¿Quieres eliminar ahora los productos vencidos? Se eliminarán los que tengan más de 1 día desde su fecha de caducidad. Esta acción es permanente.');
                if (!confirmed) return false;
                btn.disabled = true;
                btn.classList.remove('toggle-off');
                btn.classList.add('toggle-on');
                btn.setAttribute('aria-pressed', 'true');
                btn.querySelector('.toggle-label').textContent = 'ON';

                const url = "{{ route('empresa.productos.delete-expired') }}";
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                }).then(async (res) => {
                    let message = 'Limpieza ejecutada';
                    try {
                        const data = await res.json();
                        if (data && data.message) message = data.message;
                    } catch (_) {}
                    showSuccessNotification(message);
                }).catch((err) => {
                    console.error('Error al eliminar productos vencidos:', err);
                }).finally(() => {
                    // Mantener ON hasta que el usuario lo apague manualmente
                    btn.disabled = false;
                });
            } else {
                // Apagar manualmente
                btn.classList.remove('toggle-on');
                btn.classList.add('toggle-off');
                btn.setAttribute('aria-pressed', 'false');
                btn.querySelector('.toggle-label').textContent = 'OFF';
            }
            return false;
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openModalBtn = document.getElementById('openModal');
            const closeModalBtn = document.getElementById('closeModal');
            const editModal = document.getElementById('editModal');
            const openProgressiveBtn = document.getElementById('openProgressiveModal');
            const closeProgressiveBtn = document.getElementById('closeProgressiveModal');
            const progressiveModal = document.getElementById('progressiveDiscountModal');

            if(openModalBtn) {
                openModalBtn.addEventListener('click', function() {
                    editModal.style.display = 'block';
                });
            }

            if(closeModalBtn) {
                closeModalBtn.addEventListener('click', function() {
                    editModal.style.display = 'none';
                });
            }
            // Cerrar con tecla ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && editModal && editModal.style.display === 'block') {
                    editModal.style.display = 'none';
                }
            });
            
            // Abrir/cerrar modal de descuento progresivo
            if (openProgressiveBtn) {
                openProgressiveBtn.addEventListener('click', function() {
                    progressiveModal.style.display = 'block';
                });
            }
            if (closeProgressiveBtn) {
                closeProgressiveBtn.addEventListener('click', function() {
                    progressiveModal.style.display = 'none';
                });
            }

            window.addEventListener('click', function(event) {
                if (event.target === editModal) {
                    editModal.style.display = 'none';
                }
                if (event.target === progressiveModal) {
                    progressiveModal.style.display = 'none';
                }
            });

            // Manejar envío del formulario de edición
            const editForm = document.getElementById('editProfileForm');
            if(editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalText = submitButton.textContent;
                    
                    // Deshabilitar botón y mostrar estado de carga
                    submitButton.disabled = true;
                    submitButton.textContent = 'Actualizando...';
                    
                    fetch('{{ route("empresa.perfil.update") }}', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-HTTP-Method-Override': 'PUT'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Mostrar mensaje de éxito moderno
                            showSuccessNotification(data.message || 'Perfil actualizado correctamente');
                            
                            // Cerrar modal
                            editModal.style.display = 'none';
                        } else {
                            // Mostrar errores de validación
                            let errorMessage = 'Error al actualizar el perfil:\n';
                            if (data.errors) {
                                Object.values(data.errors).forEach(error => {
                                    errorMessage += '• ' + error[0] + '\n';
                                });
                            } else {
                                errorMessage += data.message || 'Error desconocido';
                            }
                            alert(errorMessage);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error de conexión. Por favor, intenta de nuevo.');
                    })
                    .finally(() => {
                        // Restaurar botón
                        submitButton.disabled = false;
                        submitButton.textContent = originalText;
                    });
                });
            }

            const welcomeModal = document.getElementById('modal-bienvenida');
            const welcomeMessage = localStorage.getItem('welcomeMessageShown');

            if (!welcomeMessage) {
                welcomeModal.classList.add('modal-visible');
                
                setTimeout(() => {
                    welcomeModal.classList.remove('modal-visible');
                    welcomeModal.style.display = 'none';
                    localStorage.setItem('welcomeMessageShown', 'true');
                }, 5000);
            }

            // Toggle de descuento progresivo
            const toggleBtn = document.getElementById('toggleProgressiveDiscountBtn');
            const statusText = document.getElementById('progressiveStatusText');
            if (toggleBtn && statusText) {
                toggleBtn.addEventListener('click', async function() {
                    toggleBtn.disabled = true;
                    const originalHtml = toggleBtn.innerHTML;
                    toggleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Procesando...';

                    try {
                        const toggleUrl = "{{ route('empresa.progressive-discount.toggle') }}";
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                        const res = await fetch(toggleUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({})
                        });
                        const data = await res.json();
                        if (data.success) {
                            const enabled = !!data.enabled;
                            statusText.textContent = enabled ? 'Activo' : 'Inactivo';
                            statusText.classList.toggle('text-success', enabled);
                            statusText.classList.toggle('text-danger', !enabled);
                            toggleBtn.classList.toggle('btn-success', !enabled);
                            toggleBtn.classList.toggle('btn-warning', enabled);
                            toggleBtn.innerHTML = `<i class="fas ${enabled ? 'fa-toggle-off' : 'fa-toggle-on'}"></i> ${enabled ? 'Desactivar' : 'Activar'} descuento progresivo`;
                            showSuccessNotification(`Descuento progresivo ${enabled ? 'activado' : 'desactivado'}.`);
                        } else {
                            alert('No se pudo alternar el estado.');
                            toggleBtn.innerHTML = originalHtml;
                        }
                    } catch (e) {
                        console.error(e);
                        alert('Error de red al alternar el estado.');
                        toggleBtn.innerHTML = originalHtml;
                    } finally {
                        toggleBtn.disabled = false;
                    }
                });
            }

            // Vista previa del logo
            const logoInput = document.querySelector('input[name="Foto"]');
            const logoPreview = document.getElementById('logoPreview');
            if (logoInput && logoPreview) {
                logoInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (file) {
                        const url = URL.createObjectURL(file);
                        logoPreview.src = url;
                    }
                });
            }

            // Vista previa del certificado
            const certInput = document.querySelector('input[name="Certificado_Camara_de_comercio"]');
            const certPreview = document.getElementById('certPreview');
            if (certInput && certPreview) {
                certInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (file) {
                        const ext = (file.name.split('.').pop() || '').toLowerCase();
                        if (['jpg', 'jpeg', 'png'].includes(ext)) {
                            const url = URL.createObjectURL(file);
                            certPreview.innerHTML = `<img class="logo-preview" src="${url}" alt="Certificado">`;
                        } else {
                            certPreview.innerHTML = `
                                <div class="w-48 h-48 rounded-2xl bg-gray-100 border-4 border-gray-200 flex items-center justify-center text-gray-600 text-5xl">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                            `;
                        }
                    }
                });
            }

            // Función para mostrar notificación de éxito moderna
            function showSuccessNotification(message) {
                // Crear el elemento de notificación
                const notification = document.createElement('div');
                notification.className = 'success-notification';
                notification.innerHTML = `
                    <div class="notification-content">
                        <div class="notification-icon">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="notification-text">
                            <div class="notification-title">¡Éxito!</div>
                            <div class="notification-message">${message}</div>
                        </div>
                        <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                `;

                // Agregar al body
                document.body.appendChild(notification);

                // Mostrar con animación
                setTimeout(() => {
                    notification.classList.add('show');
                }, 100);

                // Remover automáticamente después de 4 segundos
                setTimeout(() => {
                    notification.classList.remove('show');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }, 4000);
            }
        });

        // Función para marcar notificación como leída
        function markAsRead(notificationId) {
            fetch(`/empresa/notificaciones/${notificationId}/marcar-leida`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recargar la página para actualizar las notificaciones
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
    </script>

    <style>
        /* Estilos para la notificación de éxito moderna */
        .success-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3), 0 4px 12px rgba(0, 0, 0, 0.15);
            min-width: 320px;
            max-width: 400px;
            transform: translateX(100%);
            opacity: 0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .success-notification.show {
            transform: translateX(0);
            opacity: 1;
        }

        .notification-content {
            display: flex;
            align-items: flex-start;
            padding: 16px 20px;
            gap: 12px;
        }

        .notification-icon {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            color: white;
            margin-top: 2px;
        }

        .notification-text {
            flex: 1;
            min-width: 0;
        }

        .notification-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 2px;
            color: white;
        }

        .notification-message {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
        }

        .notification-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
            padding: 2px;
            border-radius: 4px;
            transition: all 0.2s ease;
            margin-top: 1px;
        }

        .notification-close:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        /* Responsive */
        @media (max-width: 480px) {
            .success-notification {
                right: 10px;
                left: 10px;
                min-width: auto;
                max-width: none;
            }
        }
    </style>
    
    <x-footer />
</body>
</html>
</body>
</html>