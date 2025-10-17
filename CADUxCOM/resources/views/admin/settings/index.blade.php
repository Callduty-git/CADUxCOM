@extends('layouts.admin')

@section('title', 'Configuraciones del Sistema')

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <h1>⚙️ Configuraciones del Sistema</h1>
        <p>Administra las configuraciones generales de la plataforma</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="settings-grid">
        <div class="settings-card">
            <div class="card-icon general">
                <i class="fas fa-cog"></i>
            </div>
            <div class="card-content">
                <h3>Configuraciones Generales</h3>
                <p>Información básica de la aplicación, contacto y configuraciones principales</p>
                <ul class="settings-list">
                    <li>Nombre y descripción de la aplicación</li>
                    <li>Información de contacto</li>
                    <li>Modo de mantenimiento</li>
                    <li>Configuraciones de registro</li>
                </ul>
                <a href="{{ route('admin.settings.general') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Configurar
                </a>
            </div>
        </div>

        <div class="settings-card">
            <div class="card-icon email">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="card-content">
                <h3>Configuraciones de Email</h3>
                <p>Configuración del servidor de correo y notificaciones por email</p>
                <ul class="settings-list">
                    <li>Servidor SMTP</li>
                    <li>Credenciales de email</li>
                    <li>Configuraciones de notificaciones</li>
                    <li>Emails automáticos</li>
                </ul>
                <a href="{{ route('admin.settings.email') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Configurar
                </a>
            </div>
        </div>

        <div class="settings-card">
            <div class="card-icon platform">
                <i class="fas fa-globe"></i>
            </div>
            <div class="card-content">
                <h3>Configuraciones de Plataforma</h3>
                <p>Configuraciones específicas del funcionamiento de la plataforma</p>
                <ul class="settings-list">
                    <li>Sistema de reseñas</li>
                    <li>Límites y restricciones</li>
                    <li>Cache y rendimiento</li>
                    <li>Sesiones y seguridad</li>
                </ul>
                <a href="{{ route('admin.settings.platform') }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>
                    Configurar
                </a>
            </div>
        </div>
    </div>

    <div class="settings-actions">
        <div class="actions-card">
            <h3>🔧 Acciones del Sistema</h3>
            <div class="actions-grid">
                <button onclick="clearCache()" class="action-btn cache">
                    <i class="fas fa-broom"></i>
                    <span>Limpiar Cache</span>
                </button>
                
                <a href="{{ route('admin.settings.export') }}" class="action-btn export">
                    <i class="fas fa-download"></i>
                    <span>Exportar Configuraciones</span>
                </a>
                
                <button onclick="document.getElementById('import-form').style.display='block'" class="action-btn import">
                    <i class="fas fa-upload"></i>
                    <span>Importar Configuraciones</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal de importación -->
    <div id="import-form" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Importar Configuraciones</h3>
                <button onclick="document.getElementById('import-form').style.display='none'" class="close-btn">&times;</button>
            </div>
            <form action="{{ route('admin.settings.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="settings_file">Archivo de configuraciones (JSON)</label>
                    <input type="file" id="settings_file" name="settings_file" accept=".json" required>
                    <small>Selecciona un archivo JSON con las configuraciones a importar</small>
                </div>
                <div class="modal-actions">
                    <button type="button" onclick="document.getElementById('import-form').style.display='none'" class="btn btn-secondary">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Importar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.settings-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.settings-header {
    text-align: center;
    margin-bottom: 3rem;
}

.settings-header h1 {
    font-size: 2.5rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.settings-header p {
    color: #6b7280;
    font-size: 1.1rem;
}

.alert {
    padding: 1rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
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

.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
    margin-bottom: 3rem;
}

.settings-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
    transition: transform 0.2s, box-shadow 0.2s;
}

.settings-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 15px rgba(0,0,0,0.1);
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.card-icon.general {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.card-icon.email {
    background: linear-gradient(135deg, #f093fb, #f5576c);
    color: white;
}

.card-icon.platform {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
    color: white;
}

.card-content h3 {
    font-size: 1.25rem;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.card-content p {
    color: #6b7280;
    margin-bottom: 1rem;
    line-height: 1.5;
}

.settings-list {
    list-style: none;
    padding: 0;
    margin-bottom: 1.5rem;
}

.settings-list li {
    padding: 0.25rem 0;
    color: #4b5563;
    position: relative;
    padding-left: 1rem;
}

.settings-list li::before {
    content: '•';
    color: #667eea;
    position: absolute;
    left: 0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.settings-actions {
    margin-top: 3rem;
}

.actions-card {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
}

.actions-card h3 {
    font-size: 1.25rem;
    color: #1f2937;
    margin-bottom: 1.5rem;
}

.actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1.5rem;
    border-radius: 12px;
    border: 2px solid #e5e7eb;
    background: white;
    color: #374151;
    text-decoration: none;
    transition: all 0.2s;
    cursor: pointer;
}

.action-btn:hover {
    border-color: #667eea;
    background: #f8fafc;
    transform: translateY(-1px);
}

.action-btn i {
    font-size: 1.5rem;
}

.action-btn.cache:hover { border-color: #f59e0b; }
.action-btn.export:hover { border-color: #10b981; }
.action-btn.import:hover { border-color: #3b82f6; }

.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
}

.modal-content {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.modal-header h3 {
    margin: 0;
    color: #1f2937;
}

.close-btn {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6b7280;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 500;
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
}

.form-group small {
    color: #6b7280;
    font-size: 0.875rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

@media (max-width: 768px) {
    .settings-container {
        padding: 1rem;
    }
    
    .settings-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function clearCache() {
    if (confirm('¿Estás seguro de que quieres limpiar el cache? Esto puede afectar temporalmente el rendimiento.')) {
        fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Cache limpiado correctamente');
                location.reload();
            } else {
                alert('Error al limpiar el cache');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al limpiar el cache');
        });
    }
}
</script>
@endsection