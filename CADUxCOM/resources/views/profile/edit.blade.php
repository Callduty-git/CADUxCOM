@extends('layouts.profile')

@section('title', 'Mi Perfil - CADUxCOM')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/editar-foto.css') }}">

<style>
/* Estilos específicos para el perfil de usuario */
.profile-main {
    background: #f8f9fa;
    min-height: calc(100vh - 200px);
    padding: 20px 0;
}

.profile-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.profile-header {
    background: linear-gradient(135deg, #49874E 0%, #89CF6D 100%);
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    color: white;
    display: flex;
    align-items: center;
    gap: 30px;
    box-shadow: 0 8px 25px rgba(73, 135, 78, 0.3);
}

.profile-photo-container {
    position: relative;
    flex-shrink: 0;
}

.profile-photo {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    overflow: hidden;
    border: 4px solid white;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.profile-placeholder {
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
}

.photo-edit-btn {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #AA5FC7;
    border: 5px solid white;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(170, 95, 199, 0.5);
    z-index: 100;
}

.photo-edit-btn:hover {
    background: #8B4A9E;
    transform: scale(1.1);
}

.profile-info {
    flex: 1;
}

.profile-name {
    font-size: 2.5rem;
    font-weight: 700;
    margin: 0 0 10px 0;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
}

.user-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    margin-bottom: 10px;
    backdrop-filter: blur(10px);
}

.member-since {
    color: rgba(255, 255, 255, 0.9);
    font-size: 16px;
    margin: 0;
    font-style: italic;
}

.profile-actions {
    flex-shrink: 0;
}

.btn-edit-profile {
    background: #AA5FC7;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(170, 95, 199, 0.3);
}

.btn-edit-profile:hover {
    background: #8B4A9E;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(170, 95, 199, 0.4);
}

.profile-content {
    display: grid;
    gap: 30px;
}

.profile-section {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #f8f9fa;
}

.section-title {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 1.5rem;
    font-weight: 700;
    color: #49874E;
    margin: 0;
}

.section-edit-btn {
    background: #89CF6D;
    color: white;
    border: none;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    font-size: 16px;
}

.section-edit-btn:hover {
    background: #49874E;
    transform: scale(1.1);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.info-label {
    font-weight: 600;
    color: #49874E;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.info-value-container {
    position: relative;
}

.info-value {
    display: block;
    padding: 12px 16px;
    background: #f8f9fa;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    color: #495057;
    font-size: 16px;
    min-height: 48px;
    transition: all 0.3s ease;
}

.info-value:hover {
    border-color: #89CF6D;
    background: #f8fff8;
}

.info-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #89CF6D;
    border-radius: 8px;
    font-size: 16px;
    color: #495057;
    background: #f8fff8;
    transition: all 0.3s ease;
}

.info-input:focus {
    outline: none;
    border-color: #49874E;
    box-shadow: 0 0 0 3px rgba(137, 207, 109, 0.2);
}

.edit-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 25px;
    padding-top: 20px;
    border-top: 2px solid #f8f9fa;
}

.btn-save {
    background: #89CF6D;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(137, 207, 109, 0.3);
}

.btn-save:hover {
    background: #49874E;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(137, 207, 109, 0.4);
}

.btn-cancel {
    background: #6c757d;
    color: white;
    border: none;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-cancel:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

/* Navegación de tabs */
.profile-navigation {
    margin-bottom: 30px;
}

.nav-tabs {
    display: flex;
    background: white;
    border-radius: 15px;
    padding: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    gap: 8px;
}

.nav-tab {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 15px 20px;
    border: none;
    background: transparent;
    border-radius: 10px;
    font-size: 16px;
    font-weight: 600;
    color: #6c757d;
    cursor: pointer;
    transition: all 0.3s ease;
}

.nav-tab:hover {
    background: #f8f9fa;
    color: #49874E;
}

.nav-tab.active {
    background: linear-gradient(135deg, #49874E 0%, #89CF6D 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(73, 135, 78, 0.3);
}

.nav-tab span:first-child {
    font-size: 18px;
}

/* Contenido de tabs */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

/* Estilos para la sección de pedidos */
.loading-orders {
    text-align: center;
    padding: 40px 20px;
    color: #6c757d;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #49874E;
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.order-item {
    background: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.order-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #89CF6D;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e9ecef;
}

.order-number {
    font-weight: 700;
    color: #49874E;
    font-size: 18px;
}

.order-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending { background: #fff3cd; color: #856404; }
.status-paid { background: #d1ecf1; color: #0c5460; }
.status-processing { background: #e2e3f1; color: #383d41; }
.status-shipped { background: #d4edda; color: #155724; }
.status-delivered { background: #d1ecf1; color: #0c5460; }
.status-cancelled { background: #f8d7da; color: #721c24; }

.order-details {
    display: grid;
    grid-template-columns: 1fr auto;
    gap: 20px;
    align-items: center;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.order-date {
    color: #6c757d;
    font-size: 14px;
}

.order-total {
    font-size: 18px;
    font-weight: 700;
    color: #49874E;
}

.order-actions {
    display: flex;
    gap: 10px;
}

.btn-contact-company {
    background: #AA5FC7;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-contact-company:hover {
    background: #8B4A9E;
    transform: translateY(-1px);
}

.btn-view-order {
    background: #89CF6D;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.btn-view-order:hover {
    background: #49874E;
    transform: translateY(-1px);
}

.empty-orders {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-orders-icon {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.5;
}

.settings-grid {
    display: grid;
    gap: 20px;
}

.setting-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.setting-item:hover {
    border-color: #89CF6D;
    background: #f8fff8;
    transform: translateX(5px);
}

.setting-item.danger-item {
    border-color: #dc3545;
    background: #fff5f5;
}

.setting-item.danger-item:hover {
    border-color: #c82333;
    background: #ffe6e6;
}

.setting-info {
    flex: 1;
}

.setting-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
    color: #49874E;
    margin: 0 0 5px 0;
}

.setting-description {
    color: #6c757d;
    font-size: 14px;
    margin: 0;
}

.btn-secondary {
    background: #AA5FC7;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(170, 95, 199, 0.3);
}

.btn-secondary:hover {
    background: #8B4A9E;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(170, 95, 199, 0.4);
}

.btn-danger {
    background: #dc3545;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3);
}

.btn-danger:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.4);
}

.btn-danger:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

/* Modales */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-container {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.modal-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 25px;
    border-bottom: 2px solid #f8f9fa;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #49874E;
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    color: #6c757d;
    cursor: pointer;
    padding: 5px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

.modal-close:hover {
    background: #f8f9fa;
    color: #dc3545;
}

.modal-body {
    padding: 25px;
}

.modal-footer {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    padding: 20px 25px;
    border-top: 2px solid #f8f9fa;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: #49874E;
    margin-bottom: 8px;
    font-size: 14px;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 16px;
    color: #495057;
    transition: all 0.3s ease;
}

.form-group input:focus {
    outline: none;
    border-color: #89CF6D;
    box-shadow: 0 0 0 3px rgba(137, 207, 109, 0.2);
}

.error-message {
    display: block;
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
}

.danger-modal .modal-title {
    color: #dc3545;
}

.warning-content {
    text-align: center;
}

.warning-icon {
    font-size: 48px;
    margin-bottom: 20px;
}

.warning-content h4 {
    color: #dc3545;
    font-size: 20px;
    margin-bottom: 15px;
}

.warning-content p {
    color: #6c757d;
    margin-bottom: 15px;
}

.warning-content ul {
    text-align: left;
    color: #6c757d;
    margin-bottom: 20px;
}

.warning-text {
    font-weight: 600;
    color: #dc3545;
}

.confirm-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #dc3545;
    border-radius: 8px;
    font-size: 16px;
    color: #495057;
    text-align: center;
    font-weight: 600;
    transition: all 0.3s ease;
}

.confirm-input:focus {
    outline: none;
    border-color: #c82333;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.2);
}

/* Alertas */
.alert {
    display: flex;
    align-items: center;
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 8px;
    font-weight: 500;
    animation: slideDown 0.3s ease-out;
}

.alert-success {
    background: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

.alert-error {
    background: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}

.alert-info {
    background: #d1ecf1;
    color: #0c5460;
    border: 1px solid #bee5eb;
}

.alert-icon {
    margin-right: 10px;
    font-size: 18px;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


/* Responsividad */
@media (max-width: 768px) {
    .profile-container {
        padding: 0 15px;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
        gap: 20px;
        padding: 25px 20px;
    }

    .profile-photo {
        width: 100px;
        height: 100px;
    }

    .photo-edit-btn {
        width: 35px;
        height: 35px;
        font-size: 16px;
        border: 3px solid white;
        bottom: 3px;
        right: 3px;
    }

    .profile-name {
        font-size: 2rem;
    }

    .info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .edit-actions {
        flex-direction: column;
    }

    .setting-item {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }

    .modal-container {
        width: 95%;
        margin: 20px;
    }

    .modal-footer {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .profile-header {
        padding: 20px 15px;
    }

    .profile-photo {
        width: 80px;
        height: 80px;
    }

    .photo-edit-btn {
        width: 28px;
        height: 28px;
        font-size: 14px;
        border: 2px solid white;
        bottom: 2px;
        right: 2px;
    }

    .profile-name {
        font-size: 1.5rem;
    }

    .profile-section {
        padding: 20px 15px;
    }

    .section-title {
        font-size: 1.25rem;
    }

    .info-value, .info-input {
        font-size: 14px;
        padding: 10px 12px;
    }

    .btn-save, .btn-cancel, .btn-secondary, .btn-danger {
        padding: 10px 16px;
        font-size: 14px;
    }
    
}
</style>
@endpush

@section('content')
<div class="profile-container">
    {{-- Mensajes de éxito/error --}}
    @if (session('status'))
        <div class="alert alert-success">
            <span class="alert-icon">✓</span>
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">
            <span class="alert-icon">⚠</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- ENCABEZADO DEL PERFIL --}}
    <div class="profile-header">
        <div class="profile-photo-container">
            <div class="profile-photo">
                <div class="profile-placeholder" id="profile-photo-container">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="Foto de perfil" class="profile-image">
                    @else
                        <span>👤</span>
                    @endif
                </div>
            </div>
            <button class="photo-edit-btn" onclick="openPhotoEditModal()" title="Editar foto de perfil">
                📷
            </button>
        </div>
        
        <div class="profile-info">
            <h1 class="profile-name">{{ $user->name ?? 'Usuario' }}</h1>
            <div class="user-type-badge">
                <span>🛒</span>
                <span>Consumidor</span>
            </div>
            <p class="member-since">Miembro desde {{ $user->created_at->format('M Y') }}</p>
        </div>

        <div class="profile-actions">
            <button class="btn-edit-profile" onclick="toggleEditMode()">
                ✏️ Editar Perfil
            </button>
        </div>
    </div>

    {{-- NAVEGACIÓN DEL PERFIL --}}
    <div class="profile-navigation">
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showTab('profile-info')" id="tab-profile-info">
                <span>👤</span>
                <span>Mi Información</span>
            </button>
            <button class="nav-tab" onclick="showTab('my-orders')" id="tab-my-orders">
                <span>📦</span>
                <span>Mis Pedidos</span>
            </button>
        </div>
    </div>

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="profile-content">
        {{-- TAB: INFORMACIÓN PERSONAL --}}
        <div id="profile-info" class="tab-content active">
        {{-- INFORMACIÓN PERSONAL --}}
        <div class="profile-section">
            <div class="section-header">
                <h2 class="section-title">
                    <span>👤</span>
                    Información Personal
                </h2>
                <button class="section-edit-btn" onclick="toggleEditMode()">
                    ✏️
                </button>
            </div>

            <div class="info-grid">
                {{-- Nombre --}}
                <div class="info-item">
                    <label class="info-label">Nombre</label>
                    <div class="info-value-container">
                        <span class="info-value" id="name-display">{{ $user->name ?? 'No especificado' }}</span>
                        <input type="text" class="info-input" id="name-input" name="name" value="{{ $user->name ?? '' }}" style="display: none;">
                    </div>
                </div>

                {{-- Apellido --}}
                <div class="info-item">
                    <label class="info-label">Apellido</label>
                    <div class="info-value-container">
                        <span class="info-value" id="lastname-display">{{ $user->apellido ?? 'No especificado' }}</span>
                        <input type="text" class="info-input" id="lastname-input" name="apellido" value="{{ $user->apellido ?? '' }}" style="display: none;">
                    </div>
                </div>

                {{-- Correo electrónico --}}
                <div class="info-item">
                    <label class="info-label">Correo Electrónico</label>
                    <div class="info-value-container">
                        <span class="info-value" id="email-display">{{ $user->email ?? 'No especificado' }}</span>
                        <input type="email" class="info-input" id="email-input" name="email" value="{{ $user->email ?? '' }}" style="display: none;">
                    </div>
                </div>

                {{-- Teléfono --}}
                <div class="info-item">
                    <label class="info-label">Teléfono</label>
                    <div class="info-value-container">
                        <span class="info-value" id="phone-display">{{ $user->contacto ?? 'No especificado' }}</span>
                        <input type="tel" class="info-input" id="phone-input" name="contacto" value="{{ $user->contacto ?? '' }}" style="display: none;">
                    </div>
                </div>

                {{-- Dirección --}}
                <div class="info-item">
                    <label class="info-label">Dirección</label>
                    <div class="info-value-container">
                        <span class="info-value" id="address-display">{{ $user->ubicacion ?? 'No especificado' }}</span>
                        <input type="text" class="info-input" id="address-input" name="ubicacion" value="{{ $user->ubicacion ?? '' }}" style="display: none;">
                    </div>
                </div>

                {{-- Fecha de registro --}}
                <div class="info-item">
                    <label class="info-label">Fecha de Registro</label>
                    <div class="info-value-container">
                        <span class="info-value">{{ $user->created_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>

            {{-- Formulario oculto para actualización --}}
            <form id="profile-form" method="POST" action="{{ route('profile.update') }}" style="display: none;">
                @csrf
                @method('PATCH')
            </form>

            {{-- Botones de acción para edición --}}
            <div class="edit-actions" id="edit-actions" style="display: none;">
                <button class="btn-save" onclick="saveProfileChanges()">
                    💾 Guardar Cambios
                </button>
                <button class="btn-cancel" onclick="cancelEdit()">
                    ❌ Cancelar
                </button>
            </div>
        </div>

        {{-- PREFERENCIAS Y SEGURIDAD --}}
        <div class="profile-section">
            <div class="section-header">
                <h2 class="section-title">
                    <span>⚙️</span>
                    Preferencias y Seguridad
                </h2>
            </div>

            <div class="settings-grid">
                {{-- Cambiar contraseña --}}
                <div class="setting-item">
                    <div class="setting-info">
                        <h3 class="setting-title">
                            <span>🔒</span>
                            Cambiar Contraseña
                        </h3>
                        <p class="setting-description">Actualiza tu contraseña para mantener tu cuenta segura</p>
                    </div>
                    <a href="{{ route('profile.password.change') }}" class="btn-secondary" style="text-decoration: none; display: inline-block;">
                        🔑 Cambiar
                    </a>
                </div>

                {{-- Cerrar sesión --}}
                <div class="setting-item">
                    <div class="setting-info">
                        <h3 class="setting-title">
                            <span>🚪</span>
                            Cerrar Sesión
                        </h3>
                        <p class="setting-description">Cierra tu sesión actual de forma segura</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline-form">
                        @csrf
                        <button type="submit" class="btn-secondary">
                            🚪 Cerrar Sesión
                        </button>
                    </form>
                </div>

                {{-- Eliminar cuenta --}}
                <div class="setting-item danger-item">
                    <div class="setting-info">
                        <h3 class="setting-title">
                            <span>⚠️</span>
                            Eliminar Cuenta
                        </h3>
                        <p class="setting-description">Elimina permanentemente tu cuenta y todos los datos asociados</p>
                    </div>
                    <button class="btn-danger" onclick="openDeleteModal()">
                        🗑️ Eliminar
                    </button>
                </div>
            </div>
        </div>
        </div> {{-- Fin del tab profile-info --}}

        {{-- TAB: MIS PEDIDOS --}}
        <div id="my-orders" class="tab-content" style="display: none;">
            <div class="profile-section">
                <div class="section-header">
                    <h2 class="section-title">
                        <span>📦</span>
                        Mis Pedidos
                    </h2>
                </div>

                {{-- Contenedor de pedidos --}}
                <div id="orders-container">
                    <div class="loading-orders">
                        <div class="spinner"></div>
                        <p>Cargando tus pedidos...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL PARA CAMBIAR CONTRASEÑA --}}
<div id="password-modal" class="modal-overlay" style="display: none;">
    <div class="modal-container">
        <div class="modal-header">
            <h3 class="modal-title">Cambiar Contraseña</h3>
            <button class="modal-close" onclick="closePasswordModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="password-form" method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <label for="current_password">Contraseña Actual</label>
                    <input type="password" id="current_password" name="current_password" required>
                    @error('current_password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Nueva Contraseña</label>
                    <input type="password" id="password" name="password" required>
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Nueva Contraseña</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closePasswordModal()">Cancelar</button>
            <button class="btn-save" onclick="submitPasswordForm()">Cambiar Contraseña</button>
        </div>
    </div>
</div>


{{-- MODAL PARA ELIMINAR CUENTA --}}
<div id="delete-modal" class="modal-overlay" style="display: none;">
    <div class="modal-container danger-modal">
        <div class="modal-header">
            <h3 class="modal-title">⚠️ Eliminar Cuenta</h3>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="warning-content">
                <div class="warning-icon">⚠️</div>
                <h4>¿Estás seguro de que quieres eliminar tu cuenta?</h4>
                <p>Esta acción es <strong>irreversible</strong> y eliminará:</p>
                <ul>
                    <li>Tu perfil y datos personales</li>
                    <li>Tu historial de compras</li>
                    <li>Tus listas de favoritos</li>
                    <li>Todos los datos asociados a tu cuenta</li>
                </ul>
                <p class="warning-text">Si estás seguro, escribe <strong>ELIMINAR</strong> en el campo de abajo:</p>
                <input type="text" id="confirm-delete" placeholder="Escribe ELIMINAR para confirmar" class="confirm-input">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeDeleteModal()">Cancelar</button>
            <button class="btn-danger" id="confirm-delete-btn" onclick="confirmDelete()" disabled>Eliminar Cuenta</button>
        </div>
    </div>
</div>

{{-- MODAL PARA EDITAR FOTO DE PERFIL --}}
<div id="photo-edit-modal" class="modal-overlay" style="display: none;">
    <div class="modal-container photo-edit-modal">
        <div class="modal-header">
            <h3 class="modal-title">📷 Editar Foto de Perfil</h3>
            <button class="modal-close" onclick="closePhotoEditModal()">&times;</button>
        </div>
        <div class="modal-body">
            {{-- Selector de archivo --}}
            <div class="photo-upload-section" id="photo-upload-section">
                <div class="upload-area" onclick="document.getElementById('photo-input').click()">
                    <div class="upload-icon">📁</div>
                    <h4>Selecciona una imagen</h4>
                    <p>Haz clic aquí para elegir una foto desde tu dispositivo</p>
                    <p class="upload-hint">Formatos soportados: JPG, PNG, GIF (máx. 5MB)</p>
                </div>
                <input type="file" id="photo-input" accept="image/*" style="display: none;" onchange="handlePhotoSelect(event)">
            </div>

            {{-- Editor de imagen --}}
            <div class="photo-editor-section" id="photo-editor-section" style="display: none;">
                <div class="editor-container">
                    <div class="image-preview-container">
                        <img id="image-preview" class="image-preview">
                    </div>
                    
                    {{-- Controles de edición --}}
                    <div class="editor-controls">
                        <div class="control-group">
                            <label>Rotar:</label>
                            <div class="rotation-controls">
                                <button class="control-btn" onclick="rotateImage(-90)" title="Rotar 90° izquierda">↶</button>
                                <button class="control-btn" onclick="rotateImage(90)" title="Rotar 90° derecha">↷</button>
                                <button class="control-btn" onclick="rotateImage(180)" title="Voltear">↻</button>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label>Zoom:</label>
                            <div class="zoom-controls">
                                <button class="control-btn" onclick="zoomImage(-0.1)" title="Alejar">🔍-</button>
                                <input type="range" id="zoom-slider" min="0.5" max="3" step="0.1" value="1" oninput="setZoom(this.value)">
                                <button class="control-btn" onclick="zoomImage(0.1)" title="Acercar">🔍+</button>
                            </div>
                        </div>
                        
                        <div class="control-group">
                            <label>Resetear:</label>
                            <button class="control-btn reset-btn" onclick="resetImage()" title="Restaurar imagen original">🔄</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Vista previa del resultado --}}
            <div class="photo-preview-section" id="photo-preview-section" style="display: none;">
                <h4>Vista previa:</h4>
                <div class="preview-container">
                    <div class="preview-circle">
                        <img id="final-preview" class="final-preview">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closePhotoEditModal()">Cancelar</button>
            <button class="btn-secondary" id="back-to-upload" onclick="backToUpload()" style="display: none;">← Volver</button>
            <button class="btn-save" id="save-photo-btn" onclick="savePhoto()" style="display: none;">💾 Guardar Foto</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Variables globales - DEBEN estar al inicio
let isEditMode = false;
let originalValues = {};
let currentImage = null;
let currentRotation = 0;
let currentZoom = 1;
let originalImageData = null;

// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM cargado, inicializando editor de foto...');
    
    // Verificar que todos los elementos estén presentes
    checkElements();
    
    // Inicializar drag and drop
    initializeDragAndDrop();
});

// Verificar que todos los elementos necesarios estén presentes
function checkElements() {
    const elements = [
        'photo-edit-modal',
        'photo-upload-section', 
        'photo-editor-section',
        'photo-preview-section',
        'photo-input',
        'image-preview',
        'final-preview',
        'back-to-upload',
        'save-photo-btn'
    ];
    
    elements.forEach(id => {
        const element = document.getElementById(id);
        if (element) {
            console.log(`✓ Elemento ${id} encontrado`);
        } else {
            console.error(`✗ Elemento ${id} NO encontrado`);
        }
    });
}


// Toggle modo de edición
function toggleEditMode() {
    isEditMode = !isEditMode;
    const editActions = document.getElementById('edit-actions');
    const inputs = document.querySelectorAll('.info-input');
    const displays = document.querySelectorAll('.info-value');

    if (isEditMode) {
        // Guardar valores originales
        inputs.forEach(input => {
            originalValues[input.id] = input.value;
        });

        // Mostrar inputs y ocultar displays
        inputs.forEach(input => input.style.display = 'block');
        displays.forEach(display => display.style.display = 'none');
        editActions.style.display = 'flex';
    } else {
        // Mostrar displays y ocultar inputs
        inputs.forEach(input => input.style.display = 'none');
        displays.forEach(display => display.style.display = 'block');
        editActions.style.display = 'none';
        
        // Actualizar los valores mostrados con los valores de los inputs
        document.getElementById('name-display').textContent = document.getElementById('name-input').value || 'No especificado';
        document.getElementById('lastname-display').textContent = document.getElementById('lastname-input').value || 'No especificado';
        document.getElementById('email-display').textContent = document.getElementById('email-input').value || 'No especificado';
        document.getElementById('phone-display').textContent = document.getElementById('phone-input').value || 'No especificado';
        document.getElementById('address-display').textContent = document.getElementById('address-input').value || 'No especificado';
        
        // Actualizar el nombre en el encabezado del perfil
        const profileNameElement = document.querySelector('.profile-name');
        if (profileNameElement) {
            profileNameElement.textContent = document.getElementById('name-input').value || 'Usuario';
        }
        
        // Actualizar la imagen si existe
        const container = document.getElementById('profile-photo-container');
        if (container) {
            const img = container.querySelector('.profile-image');
            if (img) {
                // La imagen ya está visible, no hacer nada
            } else {
                // Si no hay imagen, mostrar placeholder
                container.innerHTML = '<span>👤</span>';
            }
        }
    }
}

// Cancelar edición
function cancelEdit() {
    // Restaurar valores originales
    Object.keys(originalValues).forEach(key => {
        document.getElementById(key).value = originalValues[key];
    });
    
    // Restaurar el nombre en el encabezado
    const profileNameElement = document.querySelector('.profile-name');
    if (profileNameElement) {
        profileNameElement.textContent = originalValues['name-input'] || 'Usuario';
    }
    
    toggleEditMode();
}

// Guardar cambios del perfil
function saveProfileChanges() {
    // Obtener valores de los inputs
    const name = document.getElementById('name-input').value;
    const apellido = document.getElementById('lastname-input').value;
    const email = document.getElementById('email-input').value;
    const contacto = document.getElementById('phone-input').value;
    const ubicacion = document.getElementById('address-input').value;
    
    // Validar que el nombre y email no estén vacíos
    if (!name.trim()) {
        showAlert('El nombre es obligatorio', 'error');
        return;
    }
    
    if (!email.trim()) {
        showAlert('El email es obligatorio', 'error');
        return;
    }
    
    // Crear FormData
    const formData = new FormData();
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PATCH');
    formData.append('name', name);
    formData.append('apellido', apellido);
    formData.append('email', email);
    formData.append('contacto', contacto);
    formData.append('ubicacion', ubicacion);
    
    // Mostrar mensaje de carga
    showAlert('Guardando cambios...', 'info');
    
    // Enviar petición AJAX
    fetch('{{ route("profile.update") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Actualizar los valores mostrados en la sección de información
            document.getElementById('name-display').textContent = data.user.name || 'No especificado';
            document.getElementById('lastname-display').textContent = data.user.apellido || 'No especificado';
            document.getElementById('email-display').textContent = data.user.email || 'No especificado';
            document.getElementById('phone-display').textContent = data.user.contacto || 'No especificado';
            document.getElementById('address-display').textContent = data.user.ubicacion || 'No especificado';
            
            // Actualizar el nombre en el encabezado del perfil
            const profileNameElement = document.querySelector('.profile-name');
            if (profileNameElement) {
                profileNameElement.textContent = data.user.name || 'Usuario';
            }
            
            // Salir del modo de edición
            toggleEditMode();
            
            showAlert('Perfil actualizado correctamente', 'success');
        } else {
            showAlert('Error al actualizar el perfil', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Error al actualizar el perfil', 'error');
    });
}

// Modal de contraseña
function openPasswordModal() {
    document.getElementById('password-modal').style.display = 'flex';
}

function closePasswordModal() {
    document.getElementById('password-modal').style.display = 'none';
    document.getElementById('password-form').reset();
}

function submitPasswordForm() {
    document.getElementById('password-form').submit();
}

// Modal de eliminación
function openDeleteModal() {
    document.getElementById('delete-modal').style.display = 'flex';
}

function closeDeleteModal() {
    document.getElementById('delete-modal').style.display = 'none';
    document.getElementById('confirm-delete').value = '';
    document.getElementById('confirm-delete-btn').disabled = true;
}

function confirmDelete() {
    const confirmText = document.getElementById('confirm-delete').value;
    if (confirmText === 'ELIMINAR') {
        // Crear formulario de eliminación
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("profile.destroy") }}';
        
        const token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const method = document.createElement('input');
        method.type = 'hidden';
        method.name = '_method';
        method.value = 'DELETE';
        
        form.appendChild(token);
        form.appendChild(method);
        document.body.appendChild(form);
        form.submit();
    }
}

// Habilitar botón de eliminación
document.getElementById('confirm-delete').addEventListener('input', function() {
    const btn = document.getElementById('confirm-delete-btn');
    btn.disabled = this.value !== 'ELIMINAR';
});

// Mostrar alertas
function showAlert(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    
    let icon = '⚠';
    if (type === 'success') icon = '✓';
    if (type === 'info') icon = 'ℹ';
    
    alert.innerHTML = `<span class="alert-icon">${icon}</span>${message}`;
    
    const container = document.querySelector('.profile-container');
    container.insertBefore(alert, container.firstChild);
    
    // Para mensajes de info, remover más rápido
    const timeout = type === 'info' ? 2000 : 5000;
    setTimeout(() => {
        alert.remove();
    }, timeout);
}

// Cerrar modales al hacer clic fuera
window.addEventListener('click', function(event) {
    const passwordModal = document.getElementById('password-modal');
    const deleteModal = document.getElementById('delete-modal');
    const photoModal = document.getElementById('photo-edit-modal');
    
    if (event.target === passwordModal) {
        closePasswordModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
    if (event.target === photoModal) {
        closePhotoEditModal();
    }
});

// ====== FUNCIONALIDADES DEL EDITOR DE FOTO ======

// Abrir modal de edición de foto
function openPhotoEditModal() {
    document.getElementById('photo-edit-modal').style.display = 'flex';
    resetPhotoEditor();
}

// Cerrar modal de edición de foto
function closePhotoEditModal() {
    document.getElementById('photo-edit-modal').style.display = 'none';
    resetPhotoEditor();
}

// Resetear el editor de foto
function resetPhotoEditor() {
    // Mostrar sección de subida
    document.getElementById('photo-upload-section').style.display = 'block';
    document.getElementById('photo-editor-section').style.display = 'none';
    document.getElementById('photo-preview-section').style.display = 'none';
    
    // Ocultar botones
    document.getElementById('back-to-upload').style.display = 'none';
    document.getElementById('save-photo-btn').style.display = 'none';
    
    // Resetear variables
    currentImage = null;
    currentRotation = 0;
    currentZoom = 1;
    originalImageData = null;
    
    // Limpiar input
    document.getElementById('photo-input').value = '';
}

// Manejar selección de foto - VERSIÓN SIMPLIFICADA
function handlePhotoSelect(event) {
    console.log('=== handlePhotoSelect INICIADO ===');
    
    try {
        const file = event.target.files[0];
        console.log('Archivo obtenido:', file);
        
        if (!file) {
            console.log('No hay archivo seleccionado');
            return;
        }
        
        console.log('Datos del archivo:', {
            name: file.name,
            type: file.type,
            size: file.size
        });
        
        // Validación básica
        if (!file.type.startsWith('image/')) {
            alert('Por favor selecciona un archivo de imagen válido.');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) {
            alert('El archivo es demasiado grande. Máximo 5MB.');
            return;
        }
        
        // Leer archivo
        const reader = new FileReader();
        reader.onload = function(e) {
            console.log('Archivo leído exitosamente');
            
            // Crear imagen
            const img = new Image();
            img.onload = function() {
                console.log('Imagen cargada exitosamente');
                
                // Asignar datos
                currentImage = img;
                originalImageData = e.target.result;
                
                // Mostrar editor
                console.log('Mostrando editor...');
                showImageEditor();
            };
            
            img.onerror = function() {
                console.error('Error al cargar la imagen');
                alert('Error al cargar la imagen.');
            };
            
            img.src = e.target.result;
        };
        
        reader.onerror = function() {
            console.error('Error al leer el archivo');
            alert('Error al leer el archivo.');
        };
        
        reader.readAsDataURL(file);
        
    } catch (error) {
        console.error('Error en handlePhotoSelect:', error);
        alert('Error al procesar el archivo: ' + error.message);
    }
}

// Mostrar editor de imagen - VERSIÓN SIMPLIFICADA
function showImageEditor() {
    console.log('=== showImageEditor INICIADO ===');
    
    try {
        // Ocultar sección de subida
        const uploadSection = document.getElementById('photo-upload-section');
        if (uploadSection) {
            uploadSection.style.display = 'none';
            console.log('✓ Sección de subida ocultada');
        } else {
            console.error('✗ No se encontró photo-upload-section');
        }
        
        // Mostrar sección de editor
        const editorSection = document.getElementById('photo-editor-section');
        if (editorSection) {
            editorSection.style.display = 'block';
            console.log('✓ Sección de editor mostrada');
        } else {
            console.error('✗ No se encontró photo-editor-section');
        }
        
        // Mostrar sección de vista previa
        const previewSection = document.getElementById('photo-preview-section');
        if (previewSection) {
            previewSection.style.display = 'block';
            console.log('✓ Sección de vista previa mostrada');
        } else {
            console.error('✗ No se encontró photo-preview-section');
        }
        
        // Mostrar botones
        const backBtn = document.getElementById('back-to-upload');
        if (backBtn) {
            backBtn.style.display = 'inline-block';
            console.log('✓ Botón volver mostrado');
        }
        
        const saveBtn = document.getElementById('save-photo-btn');
        if (saveBtn) {
            saveBtn.style.display = 'inline-block';
            console.log('✓ Botón guardar mostrado');
        }
        
        // Mostrar imagen en el editor
        const preview = document.getElementById('image-preview');
        if (preview && originalImageData) {
            preview.src = originalImageData;
            console.log('✓ Imagen asignada al preview');
        } else {
            console.error('✗ No se pudo asignar la imagen al preview');
        }
        
        // Actualizar vista previa
        updatePreview();
        
        // Mostrar mensaje de éxito
        alert('¡Imagen cargada correctamente! Usa los controles para editarla.');
        
        // Prueba adicional: verificar que la vista previa se actualice
        setTimeout(() => {
            const preview = document.getElementById('final-preview');
            if (preview && preview.src && preview.src !== '') {
                console.log('✓ Vista previa verificada:', preview.src.substring(0, 50) + '...');
            } else {
                console.error('✗ Vista previa no se actualizó correctamente');
            }
        }, 500);
        
    } catch (error) {
        console.error('Error en showImageEditor:', error);
        alert('Error al mostrar el editor: ' + error.message);
    }
}

// Volver a la sección de subida
function backToUpload() {
    document.getElementById('photo-upload-section').style.display = 'block';
    document.getElementById('photo-editor-section').style.display = 'none';
    document.getElementById('photo-preview-section').style.display = 'none';
    document.getElementById('back-to-upload').style.display = 'none';
    document.getElementById('save-photo-btn').style.display = 'none';
}

// Rotar imagen
function rotateImage(degrees) {
    currentRotation += degrees;
    currentRotation = currentRotation % 360;
    
    const preview = document.getElementById('image-preview');
    preview.style.transform = `rotate(${currentRotation}deg) scale(${currentZoom})`;
    
    updatePreview();
}

// Zoom de imagen
function zoomImage(delta) {
    currentZoom = Math.max(0.5, Math.min(3, currentZoom + delta));
    
    const preview = document.getElementById('image-preview');
    preview.style.transform = `rotate(${currentRotation}deg) scale(${currentZoom})`;
    
    // Actualizar slider
    document.getElementById('zoom-slider').value = currentZoom;
    
    updatePreview();
}

// Establecer zoom desde slider
function setZoom(value) {
    currentZoom = parseFloat(value);
    
    const preview = document.getElementById('image-preview');
    preview.style.transform = `rotate(${currentRotation}deg) scale(${currentZoom})`;
    
    updatePreview();
}

// Resetear imagen
function resetImage() {
    currentRotation = 0;
    currentZoom = 1;
    
    const preview = document.getElementById('image-preview');
    preview.style.transform = 'rotate(0deg) scale(1)';
    
    document.getElementById('zoom-slider').value = 1;
    
    updatePreview();
    
    showPhotoMessage('Imagen restaurada a su estado original.', 'info');
}

// Actualizar vista previa - VERSIÓN SIMPLIFICADA Y FUNCIONAL
function updatePreview() {
    console.log('=== updatePreview INICIADO ===');
    
    if (!originalImageData) {
        console.error('No hay datos de imagen para la vista previa');
        return;
    }
    
    const preview = document.getElementById('final-preview');
    if (!preview) {
        console.error('No se encontró el elemento final-preview');
        return;
    }
    
    console.log('Creando vista previa circular...');
    
    // Crear imagen temporal
    const tempImg = new Image();
    tempImg.onload = function() {
        console.log('Imagen temporal cargada:', tempImg.width + 'x' + tempImg.height);
        
        // Crear canvas
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const size = 120;
        
        canvas.width = size;
        canvas.height = size;
        
        // Dibujar fondo blanco sólido para imágenes transparentes
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, size, size);
        
        // Crear máscara circular
        ctx.beginPath();
        ctx.arc(size / 2, size / 2, size / 2, 0, 2 * Math.PI);
        ctx.clip();
        
        // Dibujar fondo blanco nuevamente después del clip para asegurar fondo blanco
        // Esto es importante para imágenes PNG con transparencia que podrían mostrar fondo negro
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, size, size);
        
        // Calcular dimensiones para centrar la imagen
        const imgSize = Math.min(tempImg.width, tempImg.height);
        const scale = size / imgSize;
        const scaledWidth = tempImg.width * scale;
        const scaledHeight = tempImg.height * scale;
        
        // Centrar la imagen
        const x = (size - scaledWidth) / 2;
        const y = (size - scaledHeight) / 2;
        
        // Aplicar transformaciones si es necesario
        if (currentRotation !== 0 || currentZoom !== 1) {
            ctx.save();
            ctx.translate(size / 2, size / 2);
            ctx.rotate((currentRotation * Math.PI) / 180);
            ctx.scale(currentZoom, currentZoom);
            ctx.drawImage(tempImg, -scaledWidth/2, -scaledHeight/2, scaledWidth, scaledHeight);
            ctx.restore();
        } else {
            ctx.drawImage(tempImg, x, y, scaledWidth, scaledHeight);
        }
        
        // Actualizar vista previa
        preview.src = canvas.toDataURL();
        console.log('✓ Vista previa actualizada correctamente');
    };
    
    tempImg.onerror = function() {
        console.error('Error al cargar imagen temporal para vista previa');
        // Mostrar imagen de error
        preview.src = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTIwIiBoZWlnaHQ9IjEyMCIgdmlld0JveD0iMCAwIDEyMCAxMjAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIxMjAiIGhlaWdodD0iMTIwIiBmaWxsPSIjRjVGNUY1Ii8+CjxjaXJjbGUgY3g9IjYwIiBjeT0iNjAiIHI9IjUwIiBzdHJva2U9IiNEOUQ5RDkiIHN0cm9rZS13aWR0aD0iMiIvPgo8dGV4dCB4PSI2MCIgeT0iNjUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgZmlsbD0iIzk5OTk5OSIgdGV4dC1hbmNob3I9Im1pZGRsZSI+8J+UpDwvdGV4dD4KPC9zdmc+';
    };
    
    tempImg.src = originalImageData;
}

// Guardar foto - VERSIÓN CORREGIDA
function savePhoto() {
    console.log('=== savePhoto INICIADO ===');
    
    if (!currentImage || !originalImageData) {
        console.error('No hay imagen para guardar');
        alert('No hay imagen para guardar.');
        return;
    }
    
    // Mostrar estado de carga
    const saveBtn = document.getElementById('save-photo-btn');
    if (saveBtn) {
        saveBtn.classList.add('loading');
        saveBtn.disabled = true;
        saveBtn.textContent = '💾 Guardando...';
    }
    
    // Crear canvas para la imagen final
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    
    // Configurar tamaño del canvas (300x300 para foto de perfil)
    const size = 300;
    canvas.width = size;
    canvas.height = size;
    
    console.log('Canvas creado:', size + 'x' + size);
    
    // Crear imagen temporal
    const tempImg = new Image();
    tempImg.onload = function() {
        console.log('Imagen temporal cargada para guardar');
        
        // Dibujar fondo blanco sólido para imágenes transparentes
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, size, size);
        
        // Crear máscara circular
        ctx.beginPath();
        ctx.arc(size / 2, size / 2, size / 2, 0, 2 * Math.PI);
        ctx.clip();
        
        // Dibujar fondo blanco nuevamente después del clip para asegurar fondo blanco
        // Esto es importante para imágenes PNG con transparencia que podrían mostrar fondo negro
        ctx.fillStyle = '#FFFFFF';
        ctx.fillRect(0, 0, size, size);
        
        // Calcular dimensiones para centrar la imagen
        const imgSize = Math.min(tempImg.width, tempImg.height);
        const scale = size / imgSize;
        const scaledWidth = tempImg.width * scale;
        const scaledHeight = tempImg.height * scale;
        
        // Centrar la imagen
        const x = (size - scaledWidth) / 2;
        const y = (size - scaledHeight) / 2;
        
        // Aplicar transformaciones si es necesario
        if (currentRotation !== 0 || currentZoom !== 1) {
            ctx.save();
            ctx.translate(size / 2, size / 2);
            ctx.rotate((currentRotation * Math.PI) / 180);
            ctx.scale(currentZoom, currentZoom);
            ctx.drawImage(tempImg, -scaledWidth/2, -scaledHeight/2, scaledWidth, scaledHeight);
            ctx.restore();
        } else {
            ctx.drawImage(tempImg, x, y, scaledWidth, scaledHeight);
        }
        
        console.log('Imagen procesada, convirtiendo a blob...');
        
        // Convertir a blob
        canvas.toBlob(function(blob) {
            if (!blob) {
                console.error('Error al crear blob');
                alert('Error al procesar la imagen.');
                restoreSaveButton();
                return;
            }
            
            console.log('Blob creado:', blob.size, 'bytes');
            
            // Crear FormData
            const formData = new FormData();
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            if (!csrfToken) {
                console.error('No se encontró el token CSRF');
                alert('Error de seguridad. Recarga la página.');
                restoreSaveButton();
                return;
            }
            
            formData.append('_token', csrfToken.getAttribute('content'));
            formData.append('_method', 'PATCH');
            formData.append('foto', blob, 'profile-photo.jpg');
            
            // Agregar campos requeridos para evitar errores de validación
            const user = {!! json_encode($user) !!};
            formData.append('name', user.name || 'Usuario');
            formData.append('email', user.email || '');
            formData.append('apellido', user.apellido || '');
            formData.append('contacto', user.contacto || '');
            formData.append('ubicacion', user.ubicacion || '');
            
            console.log('Enviando petición...');
            
            // Enviar petición
            fetch('{{ route("profile.update") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Respuesta recibida:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Datos recibidos:', data);
                
                if (data.success) {
                    // Actualizar imagen en el perfil
                    const profileContainer = document.getElementById('profile-photo-container');
                    if (profileContainer && data.user.foto) {
                        profileContainer.innerHTML = `<img src="${data.user.foto}" alt="Foto de perfil" class="profile-image">`;
                        console.log('✓ Imagen actualizada en el perfil');
                    }
                    
                    alert('¡Foto de perfil actualizada correctamente!');
                    
                    // Cerrar modal después de un breve delay
                    setTimeout(() => {
                        closePhotoEditModal();
                    }, 1000);
                } else {
                    console.error('Error del servidor:', data.message);
                    alert('Error al guardar la foto: ' + (data.message || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error en la petición:', error);
                alert('Error al guardar la foto. Verifica tu conexión.');
            })
            .finally(() => {
                restoreSaveButton();
            });
        }, 'image/jpeg', 0.9);
    };
    
    tempImg.onerror = function() {
        console.error('Error al cargar imagen temporal para guardar');
        alert('Error al procesar la imagen.');
        restoreSaveButton();
    };
    
    tempImg.src = originalImageData;
}

// Función auxiliar para restaurar el botón de guardar
function restoreSaveButton() {
    const saveBtn = document.getElementById('save-photo-btn');
    if (saveBtn) {
        saveBtn.classList.remove('loading');
        saveBtn.disabled = false;
        saveBtn.textContent = '💾 Guardar Foto';
    }
}

// Función de prueba para la vista previa (llamar desde consola)
function testPreview() {
    console.log('=== TESTING PREVIEW ===');
    console.log('originalImageData:', originalImageData ? 'Presente' : 'Ausente');
    console.log('currentRotation:', currentRotation);
    console.log('currentZoom:', currentZoom);
    
    const preview = document.getElementById('final-preview');
    console.log('Elemento preview:', preview ? 'Encontrado' : 'No encontrado');
    
    if (preview) {
        console.log('Preview src actual:', preview.src);
    }
    
    // Forzar actualización
    updatePreview();
}

// Función de prueba para verificar el fondo blanco
function testWhiteBackground() {
    console.log('=== TESTING WHITE BACKGROUND ===');
    
    if (!originalImageData) {
        console.log('No hay imagen cargada para probar');
        return;
    }
    
    // Crear un canvas de prueba
    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = 120;
    canvas.height = 120;
    
    // Dibujar fondo blanco
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, 120, 120);
    
    // Crear máscara circular
    ctx.beginPath();
    ctx.arc(60, 60, 60, 0, 2 * Math.PI);
    ctx.clip();
    
    // Dibujar fondo blanco nuevamente
    ctx.fillStyle = '#FFFFFF';
    ctx.fillRect(0, 0, 120, 120);
    
    // Crear imagen temporal
    const img = new Image();
    img.onload = function() {
        ctx.drawImage(img, 0, 0, 120, 120);
        
        // Verificar que el fondo sea blanco
        const imageData = ctx.getImageData(0, 0, 120, 120);
        const data = imageData.data;
        
        // Verificar algunos píxeles del borde (que deberían ser blancos)
        let whitePixels = 0;
        let totalPixels = 0;
        
        for (let i = 0; i < data.length; i += 4) {
            const r = data[i];
            const g = data[i + 1];
            const b = data[i + 2];
            const a = data[i + 3];
            
            if (a > 0) { // Solo contar píxeles visibles
                totalPixels++;
                if (r === 255 && g === 255 && b === 255) {
                    whitePixels++;
                }
            }
        }
        
        const whitePercentage = (whitePixels / totalPixels) * 100;
        console.log(`Fondo blanco: ${whitePercentage.toFixed(1)}% de los píxeles`);
        
        if (whitePercentage > 50) {
            console.log('✅ Fondo blanco aplicado correctamente');
        } else {
            console.log('❌ El fondo no es completamente blanco');
        }
    };
    
    img.src = originalImageData;
}

// Mostrar mensajes en el modal de foto - VERSIÓN SIMPLIFICADA
function showPhotoMessage(message, type) {
    console.log(`Mensaje [${type}]:`, message);
    
    // Usar alertas simples por ahora
    if (type === 'error') {
        alert('❌ ' + message);
    } else if (type === 'success') {
        alert('✅ ' + message);
    } else {
        alert('ℹ️ ' + message);
    }
}

// Inicializar drag and drop - VERSIÓN SEGURA
function initializeDragAndDrop() {
    console.log('Inicializando drag and drop...');
    
    // Esperar un poco para que el DOM esté completamente listo
    setTimeout(() => {
        const uploadArea = document.querySelector('.upload-area');
        
        if (!uploadArea) {
            console.error('No se encontró el área de subida - reintentando...');
            // Reintentar después de un segundo
            setTimeout(() => {
                const retryArea = document.querySelector('.upload-area');
                if (retryArea) {
                    console.log('Área de subida encontrada en segundo intento');
                    setupDragAndDrop(retryArea);
                } else {
                    console.error('No se pudo encontrar el área de subida después de múltiples intentos');
                }
            }, 1000);
            return;
        }
        
        setupDragAndDrop(uploadArea);
    }, 100);
}

function setupDragAndDrop(uploadArea) {
    uploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadArea.classList.add('dragover');
    });

    uploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
    });

    uploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadArea.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                // Simular selección de archivo
                const input = document.getElementById('photo-input');
                if (input) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    
                    // Disparar evento de cambio
                    const event = new Event('change', { bubbles: true });
                    input.dispatchEvent(event);
                }
            } else {
                alert('Por favor arrastra solo archivos de imagen.');
            }
        }
    });
    
    console.log('✓ Drag and drop inicializado correctamente');
}

// Funciones para navegación de tabs
function showTab(tabId) {
    // Ocultar todos los tabs
    const allTabs = document.querySelectorAll('.tab-content');
    allTabs.forEach(tab => {
        tab.style.display = 'none';
        tab.classList.remove('active');
    });
    
    // Remover clase active de todos los botones
    const allButtons = document.querySelectorAll('.nav-tab');
    allButtons.forEach(btn => btn.classList.remove('active'));
    
    // Mostrar el tab seleccionado
    const selectedTab = document.getElementById(tabId);
    if (selectedTab) {
        selectedTab.style.display = 'block';
        selectedTab.classList.add('active');
    }
    
    // Activar el botón correspondiente
    const selectedButton = document.getElementById('tab-' + tabId);
    if (selectedButton) {
        selectedButton.classList.add('active');
    }
    
    // Si es el tab de pedidos y no se han cargado, cargarlos
    if (tabId === 'my-orders') {
        loadUserOrders();
    }
}

// Cargar pedidos del usuario
function loadUserOrders() {
    const container = document.getElementById('orders-container');
    if (!container) return;
    
    // Mostrar loading si no hay contenido cargado
    if (container.innerHTML.includes('loading-orders')) {
        fetch('/orders', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            displayOrders(data.orders);
        })
        .catch(error => {
            console.error('Error loading orders:', error);
            container.innerHTML = `
                <div class="empty-orders">
                    <div class="empty-orders-icon">❌</div>
                    <h3>Error al cargar pedidos</h3>
                    <p>No se pudieron cargar tus pedidos. Intenta recargar la página.</p>
                </div>
            `;
        });
    }
}

// Mostrar pedidos en el contenedor
function displayOrders(orders) {
    const container = document.getElementById('orders-container');
    if (!container) return;
    
    if (!orders || orders.data.length === 0) {
        container.innerHTML = `
            <div class="empty-orders">
                <div class="empty-orders-icon">📦</div>
                <h3>No tienes pedidos aún</h3>
                <p>Cuando realices tu primera compra, aparecerá aquí.</p>
                <a href="/" class="btn-view-order" style="display: inline-block; text-decoration: none; margin-top: 20px;">
                    Explorar Productos
                </a>
            </div>
        `;
        return;
    }
    
    let ordersHtml = '';
    orders.data.forEach(order => {
        const statusClass = 'status-' + order.status;
        const statusText = getStatusInSpanish(order.status);
        
        ordersHtml += `
            <div class="order-item">
                <div class="order-header">
                    <div class="order-number">Pedido #${order.order_number}</div>
                    <div class="order-status ${statusClass}">${statusText}</div>
                </div>
                <div class="order-details">
                    <div class="order-info">
                        <div class="order-date">Realizado el ${formatDate(order.created_at)}</div>
                        <div class="order-total">$${formatPrice(order.total_amount)}</div>
                        <div style="color: #6c757d; font-size: 14px;">${order.items.length} producto(s)</div>
                    </div>
                    <div class="order-actions">
                        <button class="btn-contact-company" onclick="contactCompany(${order.id})">
                            💬 Contactar Empresa
                        </button>
                        <button class="btn-view-order" onclick="viewOrder(${order.id})">
                            👁️ Ver Detalles
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = ordersHtml;
}

// Funciones auxiliares
function getStatusInSpanish(status) {
    const statusMap = {
        'pending': 'Pendiente',
        'paid': 'Pagado',
        'processing': 'Procesando',
        'shipped': 'Enviado',
        'delivered': 'Entregado',
        'cancelled': 'Cancelado'
    };
    return statusMap[status] || status;
}

function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
}

// Funciones para acciones de pedidos
function viewOrder(orderId) {
    window.open('/orders/' + orderId, '_blank');
}

function contactCompany(orderId) {
    // Obtener información del pedido para contactar a la empresa
    fetch('/orders/' + orderId, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(order => {
        // Obtener las empresas únicas del pedido
        const companies = [...new Set(order.items.map(item => item.empresa))];
        
        if (companies.length === 1) {
            // Si solo hay una empresa, contactar directamente
            const company = companies[0];
            showContactModal(company, orderId);
        } else {
            // Si hay múltiples empresas, mostrar selector
            showCompanySelector(companies, orderId);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al obtener información del pedido');
    });
}

function showContactModal(company, orderId) {
    const message = `Hola, tengo una consulta sobre mi pedido #${orderId}. ¿Podrían ayudarme?`;
    const whatsappUrl = `https://wa.me/${company.contacto}?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function showCompanySelector(companies, orderId) {
    let options = companies.map(company => 
        `<option value="${company.id}">${company.nombre}</option>`
    ).join('');
    
    const html = `
        <div class="modal-overlay" style="display: flex;">
            <div class="modal-container">
                <div class="modal-header">
                    <h3 class="modal-title">Seleccionar Empresa</h3>
                    <button class="modal-close" onclick="this.closest('.modal-overlay').remove()">&times;</button>
                </div>
                <div class="modal-body">
                    <p>Este pedido contiene productos de múltiples empresas. Selecciona con cuál quieres contactarte:</p>
                    <select id="company-selector" class="form-control" style="width: 100%; padding: 10px; margin: 15px 0;">
                        ${options}
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn-cancel" onclick="this.closest('.modal-overlay').remove()">Cancelar</button>
                    <button class="btn-save" onclick="contactSelectedCompany(${orderId})">Contactar</button>
                </div>
            </div>
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', html);
}

function contactSelectedCompany(orderId) {
    const selector = document.getElementById('company-selector');
    const companyId = selector.value;
    
    // Aquí deberías obtener la información de contacto de la empresa seleccionada
    // Por ahora, simularemos el contacto
    alert(`Contactando empresa para pedido #${orderId}`);
    
    // Cerrar modal
    document.querySelector('.modal-overlay').remove();
}
</script>
@endpush
@endsection
