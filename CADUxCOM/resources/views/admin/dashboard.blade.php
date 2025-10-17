<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administrador</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container { 
            max-width: 1200px; 
            margin: 40px auto; 
            padding: 20px; 
        }
        
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        
        .admin-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        
        .admin-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 1.1rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-left: 4px solid #667eea;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .cards { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); 
            gap: 1.5rem; 
        }
        
        .card { 
            background: white;
            border: none;
            border-radius: 16px; 
            padding: 2rem; 
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative;
            overflow: hidden;
        }
        
        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px rgba(0,0,0,0.1);
        }
        
        .card-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        
        .card-empresas .card-icon { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .card-usuarios .card-icon { background: linear-gradient(135deg, #f093fb, #f5576c); color: white; }
        .card-resenas .card-icon { background: linear-gradient(135deg, #4facfe, #00f2fe); color: white; }
        .card-reportes .card-icon { background: linear-gradient(135deg, #ffecd2, #fcb69f); color: #8b4513; }
        

        
        .card h3 { 
            margin: 0 0 0.5rem 0; 
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }
        
        .card p { 
            color: #6b7280; 
            margin: 0 0 1.5rem 0;
            line-height: 1.5;
        }
        
        .card a { 
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; 
            padding: 0.75rem 1.5rem; 
            border-radius: 10px; 
            text-decoration: none; 
            font-weight: 500;
            transition: all 0.2s;
        }
        
        .card a:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        
        .logout-section {
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .logout-btn {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .logout-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }
        
        @media (max-width: 768px) {
            .admin-container { padding: 1rem; }
            .admin-header { padding: 1.5rem; }
            .admin-header h1 { font-size: 2rem; }
            .cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <header class="main-header">
            <div class="left-section">
                <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="Logo CADUxCOM" class="logo">
                <span class="logo-text">CADUxCOM</span>
            </div>
        </header>
        <main class="content">
    <div class="admin-container">
        <div class="admin-header">
            <h1>Panel de Administrador</h1>
            <p>Bienvenido al panel de administración de CADUxCOM</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <p class="stat-number">{{ $totalEmpresas ?? 0 }}</p>
                <p class="stat-label">Empresas Registradas</p>
            </div>
            <div class="stat-card">
                <p class="stat-number">{{ $totalUsuarios ?? 0 }}</p>
                <p class="stat-label">Usuarios Activos</p>
            </div>
            <div class="stat-card">
                <p class="stat-number">{{ $totalResenas ?? 0 }}</p>
                <p class="stat-label">Reseñas Totales</p>
            </div>
            <div class="stat-card">
                <p class="stat-number">{{ $empresasPendientes ?? 0 }}</p>
                <p class="stat-label">Empresas Pendientes</p>
            </div>
        </div>

        <div class="cards">
            <div class="card card-empresas">
                <div class="card-icon">🏢</div>
                <h3>Gestión de Empresas</h3>
                <p>Administra las empresas registradas, aprueba nuevos registros y gestiona verificaciones</p>
                <a href="{{ route('admin.empresas.index') }}">
                    <span>Ver Empresas</span>
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                    </svg>
                </a>
            </div>

            <div class="card card-usuarios">
                <div class="card-icon">👥</div>
                <h3>Gestión de Usuarios</h3>
                <p>Administra los usuarios registrados, gestiona permisos y supervisa la actividad</p>
                <a href="{{ route('admin.usuarios.index') }}">
                    <span>Ver Usuarios</span>
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                    </svg>
                </a>
            </div>

            <div class="card card-resenas">
                <div class="card-icon">⭐</div>
                <h3>Gestión de Reseñas</h3>
                <p>Modera comentarios y reseñas, elimina contenido inapropiado y supervisa la calidad</p>
                <a href="{{ route('admin.comentarios.index') }}">
                    <span>Ver Reseñas</span>
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                    </svg>
                </a>
            </div>

            <div class="card card-reportes">
                <div class="card-icon">📊</div>
                <h3>Reportes y Analytics</h3>
                <p>Visualiza estadísticas detalladas, genera reportes y analiza el rendimiento de la plataforma</p>
                <a href="{{ route('admin.reports.index') }}">
                    <span>Ver Reportes</span>
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M4 8a.5.5 0 0 1 .5-.5h5.793L8.146 5.354a.5.5 0 1 1 .708-.708l3 3a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708-.708L10.293 8.5H4.5A.5.5 0 0 1 4 8z"/>
                    </svg>
                </a>
            </div>


        </div>

        <div class="logout-section">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                        <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                    </svg>
                    <span>Cerrar Sesión</span>
                </button>
            </form>
        </div>
    </div>
        </main>
    </div>
</body>
</html>