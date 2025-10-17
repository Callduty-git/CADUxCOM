<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $empresa->Nombre }} - Detalles de Empresa | CADUxCOM Admin</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 20px;
            background: #f8fafc;
            min-height: calc(100vh - 200px);
        }

        .company-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .company-logo {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .company-title h1 {
            margin: 0 0 5px 0;
            font-size: 2rem;
            font-weight: 700;
        }

        .company-title p {
            margin: 0;
            opacity: 0.9;
            font-size: 1rem;
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-left: auto;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-approved {
            background: #d1fae5;
            color: #065f46;
        }

        .status-rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-sandbox {
            background: #e0e7ff;
            color: #3730a3;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .info-card h3 {
            margin: 0 0 20px 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 10px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .info-item {
            margin-bottom: 15px;
        }

        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }

        .info-value {
            color: #374151;
            font-size: 1rem;
            word-break: break-word;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            border-left: 4px solid #667eea;
        }

        .stat-number {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #6b7280;
            font-size: 0.9rem;
        }

        .actions-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: fit-content;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            width: 100%;
            justify-content: center;
            margin-bottom: 10px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5a67d8;
            transform: translateY(-1px);
        }

        .btn-success {
            background: #10b981;
            color: white;
        }

        .btn-success:hover {
            background: #059669;
        }

        .btn-danger {
            background: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background: #dc2626;
        }

        .btn-warning {
            background: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background: #d97706;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background: #4b5563;
        }

        .products-section {
            grid-column: 1 / -1;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .product-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .product-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            background: #e5e7eb;
        }

        .product-name {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .product-price {
            color: #667eea;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            background: #f8fafc;
            padding: 15px;
            border-radius: 8px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -22px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #667eea;
        }

        .timeline-date {
            font-size: 0.8rem;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .timeline-content {
            color: #374151;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            margin-bottom: 20px;
        }

        .modal-header h3 {
            margin: 0;
            color: #374151;
        }

        .modal-body {
            margin-bottom: 20px;
        }

        .modal-footer {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: #000;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 10px 12px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.9rem;
            transition: border-color 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: #d1fae5;
            color: #065f46;
            border: 1px solid #a7f3d0;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }

        .empty-state h4 {
            margin: 0 0 10px 0;
            color: #374151;
        }

        @media (max-width: 768px) {
            .admin-container {
                padding: 10px;
            }

            .content-grid {
                grid-template-columns: 1fr;
            }

            .company-header {
                flex-direction: column;
                text-align: center;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .products-grid {
                grid-template-columns: 1fr;
            }
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
            @php
                $returnTo = request('return_to');
                $backHref = match($returnTo) {
                    'pending' => route('admin.empresas.pending'),
                    'approved' => route('admin.empresas.approved'),
                    'rejected' => route('admin.empresas.rejected'),
                    default => route('admin.empresas.index'),
                };
            @endphp
            <x-admin.back-button href="{{ $backHref }}" />
            
            <div class="admin-container">
                <!-- Header de la empresa -->
                <div class="company-header">
                    @if($empresa->Foto)
                        <img src="{{ asset('storage/' . $empresa->Foto) }}" 
                             alt="{{ $empresa->Nombre }}" 
                             class="company-logo">
                    @else
                        <div class="company-logo">🏢</div>
                    @endif
                    
                    <div class="company-title">
                        <h1>{{ $empresa->Nombre }}</h1>
                        <p>{{ $empresa->email }} • NIT: {{ $empresa->NIT }}</p>
                    </div>
                    
                    <div class="status-badge status-{{ $empresa->status }}">
                        {{ ucfirst($empresa->status) }}
                    </div>
                </div>

                <!-- Alertas -->
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="content-grid">
                    <!-- Información principal -->
                    <div>
                        <!-- Información general -->
                        <div class="info-card">
                            <h3>📋 Información General</h3>
                            <div class="info-grid">
                                <div class="info-item">
                                    <div class="info-label">Nombre de la Empresa</div>
                                    <div class="info-value">{{ $empresa->Nombre }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Email</div>
                                    <div class="info-value">{{ $empresa->email }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">NIT</div>
                                    <div class="info-value">{{ $empresa->NIT }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Contacto</div>
                                    <div class="info-value">{{ $empresa->Contacto ?? 'No especificado' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Dirección</div>
                                    <div class="info-value">{{ $empresa->Direccion ?? 'No especificada' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Categoría</div>
                                    <div class="info-value">{{ $empresa->categoria ?? 'Sin categoría' }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Fecha de Registro</div>
                                    <div class="info-value">{{ $empresa->created_at->format('d/m/Y H:i') }}</div>
                                </div>
                                <div class="info-item">
                                    <div class="info-label">Última Actualización</div>
                                    <div class="info-value">{{ $empresa->updated_at->format('d/m/Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Descripción -->
                        @if($empresa->Descripcion)
                        <div class="info-card">
                            <h3>📝 Descripción</h3>
                            <div class="info-value">{{ $empresa->Descripcion }}</div>
                        </div>
                        @endif

                        <!-- Productos de la empresa -->
                        <div class="info-card products-section">
                            <h3>🛍️ Productos ({{ $empresa->productos->count() }})</h3>
                            @if($empresa->productos->count() > 0)
                                <div class="products-grid">
                                    @foreach($empresa->productos->take(6) as $producto)
                                    <div class="product-card">
                                        @if($producto->Foto)
                                            <img src="{{ asset('storage/' . $producto->Foto) }}" 
                                                 alt="{{ $producto->Nombre }}" 
                                                 class="product-image">
                                        @else
                                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; color: #6b7280;">
                                                📦
                                            </div>
                                        @endif
                                        <div class="product-name">{{ $producto->Nombre }}</div>
                                        <div class="product-price">${{ number_format($producto->Precio, 0, ',', '.') }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                @if($empresa->productos->count() > 6)
                                    <p style="text-align: center; margin-top: 15px; color: #6b7280;">
                                        Y {{ $empresa->productos->count() - 6 }} productos más...
                                    </p>
                                @endif
                            @else
                                <div class="empty-state">
                                    <h4>📭 Sin productos</h4>
                                    <p>Esta empresa aún no ha registrado productos.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Panel lateral -->
                    <div>
                        <!-- Estadísticas -->
                        <div class="info-card">
                            <h3>📊 Estadísticas</h3>
                            <div class="stats-grid">
                                <div class="stat-card">
                                    <div class="stat-number">{{ $empresa->productos->count() }}</div>
                                    <div class="stat-label">Productos</div>
                                </div>
                                <div class="stat-card">
                                    <div class="stat-number">{{ $empresa->created_at->diffInDays(now()) }}</div>
                                    <div class="stat-label">Días registrada</div>
                                </div>
                            </div>
                        </div>

                        <!-- Acciones -->
                        <div class="actions-card">
                            <h3>⚡ Acciones</h3>
                            
                            @if($empresa->status === 'pending' || $empresa->status === 'sandbox')
                                <button onclick="showApprovalModal()" class="btn btn-success">
                                    ✅ Aprobar Empresa
                                </button>
                                <button onclick="showRejectionModal()" class="btn btn-danger">
                                    ❌ Rechazar Empresa
                                </button>
                            @endif

                            @if($empresa->status === 'rejected')
                                <button onclick="showApprovalModal()" class="btn btn-success">
                                    ✅ Aprobar Empresa
                                </button>
                            @endif

                            @if($empresa->status === 'approved')
                                <button onclick="showRejectionModal()" class="btn btn-warning">
                                    ⚠️ Suspender Empresa
                                </button>
                            @endif

                            @if($empresa->Certificado)
                                <a href="{{ route('admin.empresas.download-certificate', $empresa) }}" 
                                   class="btn btn-primary">
                                    📄 Descargar Certificado
                                </a>
                            @endif

                            @if($empresa->Foto)
                                <a href="{{ route('admin.empresas.photo', $empresa) }}" 
                                   class="btn btn-primary" 
                                   target="_blank">
                                    🖼️ Ver Foto
                                </a>
                            @endif

                            <button onclick="showDeleteModal()" class="btn btn-danger">
                                🗑️ Eliminar Empresa
                            </button>

                            <a href="{{ $backHref }}" class="btn btn-secondary">
                                ← Volver
                            </a>
                        </div>

                        <!-- Historial de cambios -->
                        <div class="info-card">
                            <h3>📅 Historial</h3>
                            <div class="timeline">
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $empresa->created_at->format('d/m/Y H:i') }}</div>
                                    <div class="timeline-content">Empresa registrada</div>
                                </div>
                                @if($empresa->updated_at != $empresa->created_at)
                                <div class="timeline-item">
                                    <div class="timeline-date">{{ $empresa->updated_at->format('d/m/Y H:i') }}</div>
                                    <div class="timeline-content">Última actualización</div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal de aprobación -->
    <div id="approvalModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>✅ Aprobar Empresa</h3>
                <span class="close" onclick="closeModal('approvalModal')">&times;</span>
            </div>
            <form method="POST" action="{{ route('admin.empresas.approve', $empresa) }}">
                @csrf
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas aprobar la empresa <strong>{{ $empresa->Nombre }}</strong>?</p>
                    <div class="form-group">
                        <label class="form-label">Nota de aprobación (opcional):</label>
                        <textarea name="approval_note" 
                                  class="form-input" 
                                  rows="3" 
                                  placeholder="Agrega una nota sobre la aprobación..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('approvalModal')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-success">
                        ✅ Aprobar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de rechazo -->
    <div id="rejectionModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>❌ Rechazar Empresa</h3>
                <span class="close" onclick="closeModal('rejectionModal')">&times;</span>
            </div>
            <form method="POST" action="{{ route('admin.empresas.reject', $empresa) }}">
                @csrf
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas rechazar la empresa <strong>{{ $empresa->Nombre }}</strong>?</p>
                    <div class="form-group">
                        <label class="form-label">Motivo del rechazo <span style="color: red;">*</span>:</label>
                        <textarea name="rejection_reason" 
                                  class="form-input" 
                                  rows="4" 
                                  placeholder="Explica el motivo del rechazo..."
                                  required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('rejectionModal')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        ❌ Rechazar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal de eliminación -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>🗑️ Eliminar Empresa</h3>
                <span class="close" onclick="closeModal('deleteModal')">&times;</span>
            </div>
            <form method="POST" action="{{ route('admin.empresas.destroy', $empresa) }}">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <p><strong>⚠️ ADVERTENCIA:</strong> Esta acción eliminará permanentemente:</p>
                    <ul style="margin: 15px 0; padding-left: 20px; color: #dc2626;">
                        <li>La empresa <strong>{{ $empresa->Nombre }}</strong></li>
                        <li>Todos sus productos ({{ $empresa->productos->count() }})</li>
                        <li>Todos los archivos asociados</li>
                        <li>Todo el historial de la empresa</li>
                    </ul>
                    <p><strong>Esta acción no se puede deshacer.</strong></p>
                    
                    <div class="form-group">
                        <label class="form-label">Para confirmar, escribe el nombre de la empresa:</label>
                        <input type="text" 
                               class="form-input" 
                               id="confirmName"
                               placeholder="{{ $empresa->Nombre }}"
                               oninput="validateDeleteForm()">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('deleteModal')">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger" id="deleteButton" disabled>
                        🗑️ Eliminar Permanentemente
                    </button>
                </div>
            </form>
        </div>
    </div>



    <script>
        function showApprovalModal() {
            document.getElementById('approvalModal').style.display = 'block';
        }

        function showRejectionModal() {
            document.getElementById('rejectionModal').style.display = 'block';
        }

        function showDeleteModal() {
            document.getElementById('deleteModal').style.display = 'block';
            document.getElementById('confirmName').value = '';
            document.getElementById('deleteButton').disabled = true;
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function validateDeleteForm() {
            const confirmName = document.getElementById('confirmName').value;
            const expectedName = '{{ $empresa->Nombre }}';
            const deleteButton = document.getElementById('deleteButton');
            
            if (confirmName === expectedName) {
                deleteButton.disabled = false;
            } else {
                deleteButton.disabled = true;
            }
        }

        // Cerrar modales al hacer clic fuera
        window.onclick = function(event) {
            const modals = ['approvalModal', 'rejectionModal', 'deleteModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal(modalId);
                }
            });
        }
    </script>
</body>
</html>







