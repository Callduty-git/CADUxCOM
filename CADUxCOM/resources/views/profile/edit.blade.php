@extends('layouts.profile')

@section('title', 'Mi Perfil - CADUxCOM')

@push('styles')
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
                <button class="photo-edit-btn" onclick="document.getElementById('photo-input').click()">
                    📷
                </button>
            </div>
            <input type="file" id="photo-input" accept="image/*" style="display: none;" onchange="handlePhotoUpload(this)">
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

    {{-- CONTENIDO PRINCIPAL --}}
    <div class="profile-content">
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
                    <button class="btn-secondary" onclick="openPasswordModal()">
                        🔑 Cambiar
                    </button>
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

@push('scripts')
<script>
let isEditMode = false;
let originalValues = {};

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

// Manejar subida de foto
function handlePhotoUpload(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        
        console.log('Archivo seleccionado:', file);
        console.log('Tipo de archivo:', file.type);
        console.log('Tamaño del archivo:', file.size);
        
        // Validar tipo de archivo
        if (!file.type.startsWith('image/')) {
            showAlert('Por favor selecciona un archivo de imagen válido', 'error');
            input.value = '';
            return;
        }
        
        // Validar tamaño (máximo 5MB)
        if (file.size > 5 * 1024 * 1024) {
            showAlert('La imagen no debe superar los 5MB', 'error');
            input.value = '';
            return;
        }
        
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'PATCH');
        formData.append('foto', file);
        formData.append('name', document.getElementById('name-input') ? document.getElementById('name-input').value : '{{ $user->name }}');
        formData.append('email', document.getElementById('email-input') ? document.getElementById('email-input').value : '{{ $user->email }}');

        console.log('Enviando petición...');
        showAlert('Subiendo foto...', 'info');

        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Respuesta recibida:', response.status);
            return response.text().then(text => {
                console.log('Respuesta del servidor:', text);
                try {
                    return JSON.parse(text);
                } catch (e) {
                    console.error('Error al parsear JSON:', e);
                    // Si no es JSON, puede ser HTML con errores de validación
                    if (response.status === 422) {
                        throw new Error('Error de validación: El archivo no cumple con los requisitos');
                    }
                    throw new Error('Respuesta del servidor no válida');
                }
            });
        })
        .then(data => {
            console.log('Datos parseados:', data);
            if (data.success) {
                // Actualizar la imagen
                const container = document.getElementById('profile-photo-container');
                
                if (container) {
                    console.log('URL de la imagen:', data.user.foto);
                    
                    // Crear la imagen y verificar que se carga
                    const img = new Image();
                    img.onload = function() {
                        console.log('Imagen cargada correctamente');
                        container.innerHTML = `<img src="${data.user.foto}" alt="Foto de perfil" class="profile-image">`;
                    };
                    img.onerror = function() {
                        console.error('Error al cargar la imagen:', data.user.foto);
                        showAlert('Error al cargar la imagen', 'error');
                    };
                    img.src = data.user.foto;
                    
                    console.log('Imagen actualizada:', data.user.foto);
                } else {
                    console.error('No se encontró el contenedor de la imagen');
                }
                
                showAlert('Foto actualizada correctamente', 'success');
            } else {
                showAlert(data.message || 'Error al actualizar la foto', 'error');
            }
        })
        .catch(error => {
            console.error('Error completo:', error);
            showAlert('Error al subir la foto: ' + error.message, 'error');
        });
        
        // Limpiar el input para permitir subir la misma imagen otra vez
        input.value = '';
    }
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
    
    if (event.target === passwordModal) {
        closePasswordModal();
    }
    if (event.target === deleteModal) {
        closeDeleteModal();
    }
});
</script>
@endpush
@endsection
