<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Reseña - Admin</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }

        .admin-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .admin-header h1 {
            margin: 0 0 0.5rem 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .back-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: white;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .back-button:hover {
            opacity: 0.8;
        }

        .review-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .review-header {
            background: #f9fafb;
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .review-meta {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .meta-item {
            display: flex;
            flex-direction: column;
        }

        .meta-label {
            font-size: 0.875rem;
            color: #6b7280;
            font-weight: 500;
            margin-bottom: 0.25rem;
        }

        .meta-value {
            color: #1f2937;
            font-weight: 600;
        }

        .review-content {
            padding: 1.5rem;
        }

        .content-section {
            margin-bottom: 1.5rem;
        }

        .content-section h3 {
            margin: 0 0 0.5rem 0;
            color: #1f2937;
            font-size: 1.125rem;
        }

        .content-text {
            background: #f9fafb;
            padding: 1rem;
            border-radius: 8px;
            border-left: 4px solid #4facfe;
            line-height: 1.6;
            color: #374151;
        }

        .replies-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .replies-header {
            background: #f9fafb;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .reply-item {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            margin-left: 2rem;
            position: relative;
        }

        .reply-item:last-child {
            border-bottom: none;
        }

        .reply-item::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .reply-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        .reply-content {
            color: #374151;
            line-height: 1.5;
        }

        .actions-section {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.2s;
            text-align: center;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4facfe, #00f2fe);
            color: white;
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            background: #dbeafe;
            color: #1e40af;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge.reply {
            background: #fef3c7;
            color: #92400e;
        }

        .empty-state {
            text-align: center;
            padding: 2rem;
            color: #6b7280;
        }

        @media (max-width: 768px) {
            .admin-container { padding: 1rem; }
            .review-meta { grid-template-columns: 1fr; }
            .actions-grid { grid-template-columns: 1fr; }
            .reply-item { margin-left: 1rem; }
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
                <a href="{{ route('admin.comentarios.index') }}" class="back-button">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                    </svg>
                    Volver a Reseñas
                </a>
                <h1>Detalle de Reseña</h1>
                <p>Información completa de la reseña seleccionada</p>
            </div>

            <!-- Información de la reseña -->
            <div class="review-card">
                <div class="review-header">
                    <div class="review-meta">
                        <div class="meta-item">
                            <span class="meta-label">Usuario</span>
                            <span class="meta-value">{{ $comentario->user->name ?? 'Usuario eliminado' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Email</span>
                            <span class="meta-value">{{ $comentario->user->email ?? 'N/A' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Fecha</span>
                            <span class="meta-value">{{ $comentario->created_at->format('d/m/Y H:i:s') }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Tipo</span>
                            <span class="meta-value">
                                @if($comentario->parent_id)
                                    <span class="badge reply">Respuesta</span>
                                @else
                                    <span class="badge">Reseña Principal</span>
                                @endif
                            </span>
                        </div>
                    </div>
                    
                    <div class="review-meta">
                        <div class="meta-item">
                            <span class="meta-label">Producto</span>
                            <span class="meta-value">{{ $comentario->producto->nombre ?? 'Producto eliminado' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">Empresa</span>
                            <span class="meta-value">{{ $comentario->empresa->nombre ?? 'Empresa eliminada' }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-label">ID</span>
                            <span class="meta-value">#{{ $comentario->id }}</span>
                        </div>
                        @if($comentario->parent_id)
                        <div class="meta-item">
                            <span class="meta-label">Responde a</span>
                            <span class="meta-value">#{{ $comentario->parent_id }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="review-content">
                    <div class="content-section">
                        <h3>Contenido de la reseña</h3>
                        <div class="content-text">{{ $comentario->contenido }}</div>
                    </div>
                </div>
            </div>

            <!-- Respuestas -->
            @if($respuestas->count() > 0)
                <div class="replies-section">
                    <div class="replies-header">
                        <h3 style="margin: 0;">Respuestas ({{ $respuestas->count() }})</h3>
                    </div>
                    
                    @foreach($respuestas as $respuesta)
                        <div class="reply-item">
                            <div class="reply-meta">
                                <span><strong>{{ $respuesta->user->name ?? 'Usuario eliminado' }}</strong></span>
                                <span>{{ $respuesta->created_at->format('d/m/Y H:i') }}</span>
                                <span>ID: #{{ $respuesta->id }}</span>
                            </div>
                            <div class="reply-content">{{ $respuesta->contenido }}</div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="replies-section">
                    <div class="empty-state">
                        <p>Esta reseña no tiene respuestas.</p>
                    </div>
                </div>
            @endif

            <!-- Acciones -->
            <div class="actions-section">
                <h3 style="margin: 0 0 1rem 0;">Acciones</h3>
                <div class="actions-grid">
                    @if($comentario->producto)
                        <a href="{{ route('productos.show', $comentario->producto) }}" class="btn btn-primary" target="_blank">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 0C5.829 0 5.556.01 4.703.048 3.85.088 3.269.222 2.76.42a3.917 3.917 0 0 0-1.417.923A3.927 3.927 0 0 0 .42 2.76C.222 3.268.087 3.85.048 4.7.01 5.555 0 5.827 0 8.001c0 2.172.01 2.444.048 3.297.04.852.174 1.433.372 1.942.205.526.478.972.923 1.417.444.445.89.719 1.416.923.51.198 1.09.333 1.942.372C5.555 15.99 5.827 16 8 16s2.444-.01 3.298-.048c.851-.04 1.434-.174 1.943-.372a3.916 3.916 0 0 0 1.416-.923c.445-.445.718-.891.923-1.417.197-.509.332-1.09.372-1.942C15.99 10.445 16 10.173 16 8s-.01-2.445-.048-3.299c-.04-.851-.175-1.433-.372-1.941a3.926 3.926 0 0 0-.923-1.417A3.911 3.911 0 0 0 13.24.42c-.51-.198-1.092-.333-1.943-.372C10.443.01 10.172 0 7.998 0h.003zm-.717 1.442h.718c2.136 0 2.389.007 3.232.046.78.035 1.204.166 1.486.275.373.145.64.319.92.599.28.28.453.546.598.92.11.281.24.705.275 1.485.039.843.047 1.096.047 3.231s-.008 2.389-.047 3.232c-.035.78-.166 1.203-.275 1.485a2.47 2.47 0 0 1-.599.919c-.28.28-.546.453-.92.598-.28.11-.704.24-1.485.276-.843.038-1.096.047-3.232.047s-2.39-.009-3.233-.047c-.78-.036-1.203-.166-1.485-.276a2.478 2.478 0 0 1-.92-.598 2.48 2.48 0 0 1-.6-.92c-.109-.281-.24-.705-.275-1.485-.038-.843-.046-1.096-.046-3.233 0-2.136.008-2.388.046-3.231.036-.78.166-1.204.276-1.486.145-.373.319-.64.599-.92.28-.28.546-.453.92-.598.282-.11.705-.24 1.485-.276.738-.034 1.024-.044 2.515-.045v.002zm4.988 1.328a.96.96 0 1 0 0 1.92.96.96 0 0 0 0-1.92zm-4.27 1.122a4.109 4.109 0 1 0 0 8.217 4.109 4.109 0 0 0 0-8.217zm0 1.441a2.667 2.667 0 1 1 0 5.334 2.667 2.667 0 0 1 0-5.334z"/>
                            </svg>
                            Ver Producto
                        </a>
                    @endif

                    @if($comentario->empresa)
                        <a href="{{ route('empresas.show', $comentario->empresa) }}" class="btn btn-primary" target="_blank">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M2.5 14V1.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 .5.5V14H2.5zm1-13v12h8V1.5H3.5z"/>
                                <path d="M4.5 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V3zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H8a.5.5 0 0 1-.5-.5V3zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V3zm-6 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V6zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H8a.5.5 0 0 1-.5-.5V6zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V6zm-6 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H5a.5.5 0 0 1-.5-.5V9zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5H8a.5.5 0 0 1-.5-.5V9zm3 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5V9z"/>
                            </svg>
                            Ver Empresa
                        </a>
                    @endif

                    <form method="POST" action="{{ route('admin.comentarios.destroy', $comentario) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta reseña? Esta acción también eliminará todas sus respuestas.')">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                                <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                            </svg>
                            Eliminar Reseña
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    </div>

</body>
</html>