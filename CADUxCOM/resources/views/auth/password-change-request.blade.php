@extends('layouts.app')

@section('title', 'Cambiar Contraseña - CADUxCOM')

@push('styles')
<style>
/* Estilos específicos para cambio de contraseña */
.password-change-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 0 20px;
}

.password-change-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    padding: 40px;
    border: 1px solid #e9ecef;
}

.password-change-header {
    text-align: center;
    margin-bottom: 30px;
}

.password-change-icon {
    font-size: 48px;
    margin-bottom: 15px;
    color: #49874E;
}

.password-change-title {
    color: #49874E;
    font-size: 1.8rem;
    font-weight: 700;
    margin: 0 0 10px 0;
}

.password-change-subtitle {
    color: #6c757d;
    font-size: 1rem;
    margin: 0;
}

.form-group {
    margin-bottom: 25px;
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
    box-sizing: border-box;
}

.form-group input:focus {
    outline: none;
    border-color: #89CF6D;
    box-shadow: 0 0 0 3px rgba(137, 207, 109, 0.2);
}

.form-group input.error {
    border-color: #dc3545;
}

.error-message {
    display: block;
    color: #dc3545;
    font-size: 14px;
    margin-top: 5px;
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

@media (max-width: 600px) {
    .password-change-container {
        margin: 20px auto;
        padding: 0 15px;
    }
    
    .password-change-card {
        padding: 30px 20px;
    }
    
    .password-change-title {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@section('content')
<div class="password-change-container">
    <div class="password-change-card">
        <div class="password-change-header">
            <div class="password-change-icon">🔒</div>
            <h1 class="password-change-title">Cambiar Contraseña</h1>
            <p class="password-change-subtitle">Por seguridad, necesitamos verificar tu identidad</p>
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

        <form method="POST" action="{{ route('password.send-verification') }}">
            @csrf
            
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="Ingresa tu correo electrónico"
                    required
                    class="@error('email') error @enderror"
                >
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-send-verification">
                📧 Enviar Enlace de Verificación
            </button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Volver al Inicio de Sesión</a>
        </div>

        <div class="security-info">
            <h4>🛡️ Información de Seguridad</h4>
            <ul>
                <li>Te enviaremos un enlace de verificación a tu correo electrónico</li>
                <li>El enlace expirará en 1 hora por seguridad</li>
                <li>Solo puedes usar el enlace una vez</li>
                <li>Si no solicitaste este cambio, ignora el correo</li>
            </ul>
        </div>
    </div>
</div>
@endsection



