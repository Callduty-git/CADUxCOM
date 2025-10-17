<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CADUxCOM')</title>
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
            padding: 0;
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
            overflow: visible;
            max-height: none;
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

        @yield('styles')
    </style>
</head>
<body>
    @include('components.header-empresa')
    
    <div class="main-content">
        @yield('content')
    </div>

    @include('components.footer')

    @yield('scripts')
</body>
</html>