<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reglas de Descuento - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-empresa.css') }}">
    <link rel="stylesheet" href="{{ asset('css/discount-rules.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-header-empresa />
    
    <div class="page-container">
        <div class="discount-rules-container">
        
        <!-- Header -->
        <div class="page-header stacked">
            <div class="header-content">
                <h1 class="page-title">Reglas de Descuento Progresivo</h1>
                <p class="page-subtitle">Gestiona los descuentos automáticos basados en la proximidad a la fecha de caducidad</p>
            </div>
            <div class="header-actions">
                @php($limit = (int) config('discount.rules_limit', 5))
                @if($stats['total_rules'] < $limit)
                    <a href="{{ route('discount-rules.discount-rules.create') }}" class="btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Nueva Regla
                    </a>
                @else
                    <span class="limit-message">Has alcanzado el límite de {{ $limit }} reglas.</span>
                @endif
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $stats['total_rules'] }}</h3>
                    <p class="stat-label">Total de Reglas</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $stats['active_rules'] }}</h3>
                    <p class="stat-label">Reglas Activas</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">{{ $stats['total_usage'] }}</h3>
                    <p class="stat-label">Usos Totales</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="stat-content">
                    <h3 class="stat-value">${{ number_format($stats['total_savings'], 0, ',', '.') }}</h3>
                    <p class="stat-label">Ahorro Generado</p>
                </div>
            </div>
        </div>

        <!-- Lista de reglas -->
        <div class="rules-section">
            <div class="section-header">
                <h2 class="section-title">Reglas Configuradas</h2>
                <p class="section-subtitle">{{ $discountRules->total() }} reglas encontradas</p>
            </div>

            @if($discountRules->count() > 0)
                <div class="rules-grid">
                    @foreach($discountRules as $rule)
                        <div class="rule-card {{ $rule->is_active ? 'active' : 'inactive' }}">
                            <div class="rule-header">
                                <div class="rule-title-section">
                                    <h3 class="rule-title">{{ $rule->name }}</h3>
                                    <div class="rule-status">
                                        @if($rule->is_active)
                                            <span class="status-badge active">Activa</span>
                                        @else
                                            <span class="status-badge inactive">Inactiva</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="rule-actions">
                                    <a href="{{ route('discount-rules.discount-rules.show', $rule->id) }}" class="action-btn view">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ route('discount-rules.discount-rules.edit', $rule->id) }}" class="action-btn edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('discount-rules.toggle', $rule->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="action-btn toggle">
                                            @if($rule->is_active)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            @endif
                                        </button>
                                    </form>
                                    <form action="{{ route('discount-rules.discount-rules.destroy', $rule->id) }}" method="POST" class="inline delete-form" data-message="¿Estás seguro de eliminar esta regla?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <div class="rule-content">
                                @if($rule->description)
                                    <p class="rule-description">{{ $rule->description }}</p>
                                @endif

                                <div class="rule-details">
                                    <div class="detail-item">
                                        <span class="detail-label">Días antes de caducidad:</span>
                                        <span class="detail-value">{{ $rule->days_before_expiry }} días</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Tipo de descuento:</span>
                                        <span class="detail-value">
                                            @if($rule->discount_type === 'percentage')
                                                {{ $rule->discount_value }}% de descuento
                                            @else
                                                ${{ number_format($rule->discount_value, 0, ',', '.') }} de descuento
                                            @endif
                                        </span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Usos:</span>
                                        <span class="detail-value">{{ $rule->usage_count }} veces</span>
                                    </div>
                                    <div class="detail-item">
                                        <span class="detail-label">Ahorro generado:</span>
                                        <span class="detail-value">${{ number_format($rule->total_savings, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Botón de regreso al Dashboard de la empresa -->
                <div class="pagination-container">
                    <a href="{{ route('empresa.dashboard') }}" class="btn btn-secondary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Regresar al Dashboard
                    </a>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="empty-title">No hay reglas de descuento</h3>
                    <p class="empty-description">Crea tu primera regla de descuento progresivo para empezar a ofrecer descuentos automáticos basados en la proximidad a la fecha de caducidad.</p>
                    <div class="empty-actions">
                        <a href="{{ route('discount-rules.discount-rules.create') }}" class="btn btn-primary">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Crear Primera Regla
                        </a>
                        <form action="{{ route('discount-rules.create-defaults') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Restablecer reglas predeterminadas
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-footer />
    
    <x-edit-empresa-modal />

    <script>
        // Mostrar mensajes de éxito/error sin directivas Blade dentro de JS
        const successMsg = `{{ session('success') }}`;
        if (successMsg) {
            showNotification(successMsg, 'success');
        }

        const errorMsg = `{{ session('error') }}`;
        if (errorMsg) {
            showNotification(errorMsg, 'error');
        }

        function showNotification(message, type) {
            // Contenedor global de toasts
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.style.position = 'fixed';
                container.style.top = '16px';
                container.style.right = '16px';
                container.style.zIndex = '9999';
                container.style.display = 'flex';
                container.style.flexDirection = 'column';
                container.style.gap = '8px';
                document.body.appendChild(container);
            }

            const notification = document.createElement('div');
            notification.className = 'toast-notification';
            notification.style.display = 'flex';
            notification.style.alignItems = 'center';
            notification.style.gap = '8px';
            notification.style.padding = '12px 14px';
            notification.style.borderRadius = '10px';
            notification.style.boxShadow = '0 10px 20px rgba(0,0,0,0.12)';
            notification.style.color = '#fff';
            notification.style.transform = 'translateX(120%)';
            notification.style.transition = 'transform 240ms ease, opacity 240ms ease';
            notification.style.opacity = '0.95';
            notification.style.background = type === 'success' ? '#16a34a' : '#ef4444';

            // Icono
            const icon = document.createElement('span');
            icon.innerHTML = type === 'success' 
                ? '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
                : '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 105.636 5.636a9 9 0 0012.728 12.728z" /></svg>';

            // Texto
            const text = document.createElement('span');
            text.textContent = message;

            // Botón cerrar
            const close = document.createElement('button');
            close.setAttribute('aria-label', 'Cerrar');
            close.textContent = '×';
            close.style.marginLeft = '8px';
            close.style.background = 'transparent';
            close.style.color = 'inherit';
            close.style.border = 'none';
            close.style.fontSize = '18px';
            close.style.cursor = 'pointer';
            close.addEventListener('click', () => {
                notification.style.transform = 'translateX(120%)';
                setTimeout(() => notification.remove(), 240);
            });

            notification.appendChild(icon);
            notification.appendChild(text);
            notification.appendChild(close);
            container.appendChild(notification);

            requestAnimationFrame(() => {
                notification.style.transform = 'translateX(0)';
            });

            setTimeout(() => {
                notification.style.transform = 'translateX(120%)';
                setTimeout(() => {
                    if (notification && notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 240);
            }, 3500);
        }

        // Sin confirmación: el servidor enviará el mensaje de éxito en sesión
    </script>
</body>
</html>
