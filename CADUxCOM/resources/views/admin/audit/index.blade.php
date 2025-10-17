@extends('layouts.admin')

@section('title', 'Sistema de Auditoría y Logs')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Sistema de Auditoría y Logs</h1>
            <p class="text-muted">Monitoreo y seguimiento de actividades del sistema</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger" onclick="cleanOldLogs()">
                <i class="fas fa-trash"></i> Limpiar Logs Antiguos
            </button>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-download"></i> Exportar
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('admin.audit.export', 'system') }}">Logs del Sistema</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.audit.export', 'user') }}">Actividad de Usuarios</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.audit.export', 'company') }}">Actividad de Empresas</a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.audit.export', 'security') }}">Logs de Seguridad</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Logs
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_logs'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Logs Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['today_logs'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Alertas de Seguridad
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['security_alerts'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shield-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Usuarios Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['active_users'] ?? 0 }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card audit-nav-card h-100" onclick="location.href='{{ route('admin.audit.system') }}'">
                <div class="card-body text-center">
                    <div class="audit-icon-system mb-3">
                        <i class="fas fa-server fa-3x"></i>
                    </div>
                    <h5 class="card-title">Logs del Sistema</h5>
                    <p class="card-text text-muted">Errores, advertencias y eventos del sistema</p>
                    <span class="badge badge-primary">{{ $stats['system_logs'] ?? 0 }} registros</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card audit-nav-card h-100" onclick="location.href='{{ route('admin.audit.user-activity') }}'">
                <div class="card-body text-center">
                    <div class="audit-icon-user mb-3">
                        <i class="fas fa-user-clock fa-3x"></i>
                    </div>
                    <h5 class="card-title">Actividad de Usuarios</h5>
                    <p class="card-text text-muted">Acciones realizadas por los usuarios</p>
                    <span class="badge badge-success">{{ $stats['user_activities'] ?? 0 }} registros</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card audit-nav-card h-100" onclick="location.href='{{ route('admin.audit.company-activity') }}'">
                <div class="card-body text-center">
                    <div class="audit-icon-company mb-3">
                        <i class="fas fa-building fa-3x"></i>
                    </div>
                    <h5 class="card-title">Actividad de Empresas</h5>
                    <p class="card-text text-muted">Cambios y acciones en empresas</p>
                    <span class="badge badge-info">{{ $stats['company_activities'] ?? 0 }} registros</span>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card audit-nav-card h-100" onclick="location.href='{{ route('admin.audit.security') }}'">
                <div class="card-body text-center">
                    <div class="audit-icon-security mb-3">
                        <i class="fas fa-shield-alt fa-3x"></i>
                    </div>
                    <h5 class="card-title">Logs de Seguridad</h5>
                    <p class="card-text text-muted">Intentos de acceso y eventos de seguridad</p>
                    <span class="badge badge-warning">{{ $stats['security_logs'] ?? 0 }} registros</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actividad Reciente</h6>
                </div>
                <div class="card-body">
                    @if(isset($recent_logs) && count($recent_logs) > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tiempo</th>
                                        <th>Tipo</th>
                                        <th>Descripción</th>
                                        <th>Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_logs as $log)
                                        <tr>
                                            <td>{{ $log->created_at->format('H:i:s') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $log->level_color ?? 'secondary' }}">
                                                    {{ $log->type ?? $log->level ?? $log->action }}
                                                </span>
                                            </td>
                                            <td>{{ Str::limit($log->description ?? $log->message, 50) }}</td>
                                            <td>{{ $log->user->name ?? 'Sistema' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center">No hay actividad reciente</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Distribución por Nivel</h6>
                </div>
                <div class="card-body">
                    @if(isset($level_distribution) && count($level_distribution) > 0)
                        @foreach($level_distribution as $level)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="text-capitalize">{{ $level->level }}</span>
                                    <span>{{ $level->count }}</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-{{ $level->color ?? 'primary' }}" 
                                         style="width: {{ $level->percentage }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No hay datos disponibles</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Clean Logs Modal -->
<div class="modal fade" id="cleanLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Limpiar Logs Antiguos</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.audit.clean') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="days" class="form-label">Eliminar logs anteriores a:</label>
                        <select class="form-select" name="days" id="days" required>
                            <option value="30">30 días</option>
                            <option value="60">60 días</option>
                            <option value="90" selected>90 días</option>
                            <option value="180">180 días</option>
                            <option value="365">1 año</option>
                        </select>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Esta acción no se puede deshacer. Los logs eliminados no podrán ser recuperados.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Eliminar Logs</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.audit-nav-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 1px solid #e3e6f0;
}

.audit-nav-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.audit-icon-system i {
    background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.audit-icon-user i {
    background: linear-gradient(45deg, #f093fb 0%, #f5576c 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.audit-icon-company i {
    background: linear-gradient(45deg, #4facfe 0%, #00f2fe 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.audit-icon-security i {
    background: linear-gradient(45deg, #43e97b 0%, #38f9d7 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
</style>

<script>
function cleanOldLogs() {
    const modal = new bootstrap.Modal(document.getElementById('cleanLogsModal'));
    modal.show();
}
</script>
@endsection