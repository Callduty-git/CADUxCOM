@extends('layouts.admin')

@section('title', 'Logs del Sistema')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Logs del Sistema</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.audit.index') }}">Auditoría</a></li>
                    <li class="breadcrumb-item active">Logs del Sistema</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.audit.export', 'system') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Exportar CSV
            </a>
            <a href="{{ route('admin.audit.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filtros</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.audit.system') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="level">Nivel</label>
                            <select class="form-control" name="level" id="level">
                                <option value="">Todos los niveles</option>
                                <option value="emergency" {{ request('level') == 'emergency' ? 'selected' : '' }}>Emergency</option>
                                <option value="alert" {{ request('level') == 'alert' ? 'selected' : '' }}>Alert</option>
                                <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>Critical</option>
                                <option value="error" {{ request('level') == 'error' ? 'selected' : '' }}>Error</option>
                                <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>Warning</option>
                                <option value="notice" {{ request('level') == 'notice' ? 'selected' : '' }}>Notice</option>
                                <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>Info</option>
                                <option value="debug" {{ request('level') == 'debug' ? 'selected' : '' }}>Debug</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="module">Módulo</label>
                            <select class="form-control" name="module" id="module">
                                <option value="">Todos los módulos</option>
                                <option value="auth" {{ request('module') == 'auth' ? 'selected' : '' }}>Autenticación</option>
                                <option value="users" {{ request('module') == 'users' ? 'selected' : '' }}>Usuarios</option>
                                <option value="companies" {{ request('module') == 'companies' ? 'selected' : '' }}>Empresas</option>
                                <option value="products" {{ request('module') == 'products' ? 'selected' : '' }}>Productos</option>
                                <option value="reviews" {{ request('module') == 'reviews' ? 'selected' : '' }}>Reseñas</option>
                                <option value="admin" {{ request('module') == 'admin' ? 'selected' : '' }}>Administración</option>
                                <option value="system" {{ request('module') == 'system' ? 'selected' : '' }}>Sistema</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="start_date">Fecha Inicio</label>
                            <input type="date" class="form-control" name="start_date" id="start_date" value="{{ request('start_date') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="end_date">Fecha Fin</label>
                            <input type="date" class="form-control" name="end_date" id="end_date" value="{{ request('end_date') }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Buscar en mensaje</label>
                            <input type="text" class="form-control" name="search" id="search" 
                                   placeholder="Buscar en mensajes..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <div class="form-group w-100">
                            <button type="submit" class="btn btn-primary mr-2">
                                <i class="fas fa-search"></i> Filtrar
                            </button>
                            <a href="{{ route('admin.audit.system') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Logs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Logs del Sistema 
                @if(isset($logs))
                    ({{ $logs->total() }} registros)
                @endif
            </h6>
        </div>
        <div class="card-body">
            @if(isset($logs) && $logs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="logsTable">
                        <thead>
                            <tr>
                                <th>Fecha/Hora</th>
                                <th>Nivel</th>
                                <th>Módulo</th>
                                <th>Mensaje</th>
                                <th>Usuario</th>
                                <th>IP</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $log->level_color ?? 'secondary' }}">
                                            {{ strtoupper($log->level) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light">{{ $log->module ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <div class="log-message" title="{{ $log->message }}">
                                            {{ Str::limit($log->message, 80) }}
                                        </div>
                                    </td>
                                    <td>{{ $log->user->name ?? 'Sistema' }}</td>
                                    <td>{{ $log->ip_address ?? 'N/A' }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" 
                                                onclick="showLogDetails({{ $log->id }})" 
                                                title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No se encontraron logs del sistema con los filtros aplicados.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="logDetailsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<style>
.log-message {
    max-width: 300px;
    word-wrap: break-word;
}

.badge-emergency { background-color: #dc3545; }
.badge-alert { background-color: #fd7e14; }
.badge-critical { background-color: #e83e8c; }
.badge-error { background-color: #dc3545; }
.badge-warning { background-color: #ffc107; color: #212529; }
.badge-notice { background-color: #17a2b8; }
.badge-info { background-color: #6f42c1; }
.badge-debug { background-color: #6c757d; }
</style>

<script>
function showLogDetails(logId) {
    const modal = new bootstrap.Modal(document.getElementById('logDetailsModal'));
    const content = document.getElementById('logDetailsContent');
    
    // Show loading
    content.innerHTML = `
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Cargando...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    // Simulate API call to get log details
    setTimeout(() => {
        content.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <strong>ID:</strong> ${logId}<br>
                    <strong>Nivel:</strong> <span class="badge badge-danger">ERROR</span><br>
                    <strong>Módulo:</strong> auth<br>
                    <strong>Fecha:</strong> ${new Date().toLocaleString()}<br>
                </div>
                <div class="col-md-6">
                    <strong>Usuario:</strong> Sistema<br>
                    <strong>IP:</strong> 192.168.1.100<br>
                    <strong>URL:</strong> /admin/login<br>
                    <strong>Método:</strong> POST<br>
                </div>
            </div>
            <hr>
            <div class="mb-3">
                <strong>Mensaje:</strong>
                <div class="bg-light p-3 rounded mt-2">
                    Intento de acceso fallido para el usuario admin desde IP 192.168.1.100
                </div>
            </div>
            <div class="mb-3">
                <strong>Contexto:</strong>
                <pre class="bg-light p-3 rounded mt-2"><code>{
    "user_agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36",
    "attempt_count": 3,
    "last_attempt": "2024-01-15 10:30:45"
}</code></pre>
            </div>
        `;
    }, 1000);
}
</script>
@endsection