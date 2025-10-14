@extends('layouts.profile')

@section('title', 'Cambiar Contraseña - CADUxCOM')

@push('styles')
<style>
/* Estilos específicos para cambio de contraseña desde perfil */
.password-verification-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.password-verification-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 40px;
    border: 1px solid #e9ecef;
}

.password-verification-header {
    text-align: center;
    margin-bottom: 30px;
}

.password-verification-icon {
    font-size: 48px;
    margin-bottom: 15px;
    color: #49874E;
}

.password-verification-title {
    color: #49874E;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.password-verification-subtitle {
    color: #6c757d;
    font-size: 1rem;
    margin: 0;
}

.user-info {
    background: linear-gradient(135deg, #f8fff8 0%, #e8f5e8 100%);
    border: 2px solid #89CF6D;
    border-radius: 12px;
    padding: 20px;
    margin: 25px 0;
    text-align: center;
}

.user-info h3 {
    color: #49874E;
    margin: 0 0 10px 0;
    font-size: 18px;
}

.user-info p {
    margin: 0;
    color: #666;
    font-size: 16px;
}

.btn-send-verification {
    width: 100%;
    background: linear-gradient(135deg, #49874E 0%, #89CF6D 100%);
    color: white;
    border: none;
    padding: 15px 20px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(73, 135, 78, 0.3);
    margin: 25px 0;
}

.btn-send-verification:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(73, 135, 78, 0.4);
}

.btn-send-verification:disabled {
    background: #6c757d;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.back-link {
    text-align: center;
    margin-top: 25px;
}

.back-link a {
    color: #49874E;
    text-decoration: none;
    font-weight: 600;
    transition: color 0.3s ease;
}

.back-link a:hover {
    color: #89CF6D;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    font-weight: 500;
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

.security-info {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 20px;
    margin-top: 25px;
    border-left: 4px solid #49874E;
}

.security-info h4 {
    color: #49874E;
    margin: 0 0 10px 0;
    font-size: 16px;
}

.security-info ul {
    margin: 0;
    padding-left: 20px;
    color: #6c757d;
}

.security-info li {
    margin-bottom: 5px;
}

.process-steps {
    display: flex;
    justify-content: space-between;
    margin: 30px 0;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.step {
    text-align: center;
    flex: 1;
    position: relative;
}

.step:not(:last-child)::after {
    content: '→';
    position: absolute;
    right: -15px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 20px;
    color: #89CF6D;
    font-weight: bold;
}

.step-icon {
    font-size: 32px;
    margin-bottom: 10px;
    color: #49874E;
}

.step-text {
    font-size: 14px;
    color: #6c757d;
    font-weight: 600;
}

@media (max-width: 600px) {
    .password-verification-container {
        padding: 15px;
    }
    
    .password-verification-card {
        padding: 30px 20px;
    }
    
    .password-verification-title {
        font-size: 1.5rem;
    }
    
    .process-steps {
        flex-direction: column;
        gap: 20px;
    }
    
    .step:not(:last-child)::after {
        content: '↓';
        right: auto;
        bottom: -15px;
        top: auto;
        transform: none;
    }
}
</style>
@endpush

@section('content')
<div class="password-verification-container">
    <div class="password-verification-card">
        <div class="password-verification-header">
            <div class="password-verification-icon">🔒</div>
            <h1 class="password-verification-title">Cambiar Contraseña</h1>
            <p class="password-verification-subtitle">Por seguridad, necesitamos verificar tu identidad</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                ❌ {{ session('error') }}
            </div>
        @endif

        <div class="user-info">
            <h3>👤 Usuario Verificado</h3>
            <p><strong>{{ $user->name }}</strong></p>
            <p>{{ $user->email }}</p>
        </div>

        <div class="process-steps">
            <div class="step">
                <div class="step-icon">📧</div>
                <div class="step-text">Enviar Email</div>
            </div>
            <div class="step">
                <div class="step-icon">🔗</div>
                <div class="step-text">Hacer Clic</div>
            </div>
            <div class="step">
                <div class="step-icon">🔑</div>
                <div class="step-text">Nueva Contraseña</div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.password.send-verification') }}">
            @csrf
            
            <button type="submit" class="btn-send-verification">
                📧 Enviar Enlace de Verificación a mi Correo
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('profile.edit') }}">← Volver al Perfil</a>
        </div>

        <div class="security-info">
            <h4>🛡️ Información de Seguridad</h4>
            <ul>
                <li>Te enviaremos un enlace de verificación a tu correo electrónico registrado</li>
                <li>El enlace expirará en 1 hora por seguridad</li>
                <li>Solo puedes usar el enlace una vez</li>
                <li>Después del cambio, deberás iniciar sesión nuevamente</li>
                <li>Si no solicitaste este cambio, ignora el correo</li>
            </ul>
        </div>
    </div>
</div>
@endsection


