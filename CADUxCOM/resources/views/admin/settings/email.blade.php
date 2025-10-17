@extends('layouts.admin')

@section('title', 'Configuraciones de Email')

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <div class="header-content">
            <a href="{{ route('admin.settings.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver a Configuraciones
            </a>
            <h1>📧 Configuraciones de Email</h1>
            <p>Configura el servidor de correo y las notificaciones por email</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.settings.email.update') }}" method="POST" class="settings-form">
        @csrf
        @method('PUT')

        <div class="form-sections">
            <!-- Configuración del Servidor SMTP -->
            <div class="form-section">
                <div class="section-header">
                    <h3>🌐 Configuración del Servidor SMTP</h3>
                    <p>Configuraciones del servidor de correo saliente</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="mail_driver">Driver de Email *</label>
                        <select id="mail_driver" name="mail_driver" required>
                            <option value="smtp" {{ old('mail_driver', $settings['mail_driver']) == 'smtp' ? 'selected' : '' }}>SMTP</option>
                            <option value="sendmail" {{ old('mail_driver', $settings['mail_driver']) == 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                            <option value="mailgun" {{ old('mail_driver', $settings['mail_driver']) == 'mailgun' ? 'selected' : '' }}>Mailgun</option>
                            <option value="ses" {{ old('mail_driver', $settings['mail_driver']) == 'ses' ? 'selected' : '' }}>Amazon SES</option>
                            <option value="postmark" {{ old('mail_driver', $settings['mail_driver']) == 'postmark' ? 'selected' : '' }}>Postmark</option>
                            <option value="log" {{ old('mail_driver', $settings['mail_driver']) == 'log' ? 'selected' : '' }}>Log (Solo desarrollo)</option>
                        </select>
                    </div>

                    <div class="form-group smtp-field">
                        <label for="mail_host">Servidor SMTP *</label>
                        <input type="text" id="mail_host" name="mail_host" value="{{ old('mail_host', $settings['mail_host']) }}" placeholder="smtp.gmail.com">
                    </div>

                    <div class="form-group smtp-field">
                        <label for="mail_port">Puerto SMTP *</label>
                        <input type="number" id="mail_port" name="mail_port" value="{{ old('mail_port', $settings['mail_port']) }}" placeholder="587">
                    </div>

                    <div class="form-group smtp-field">
                        <label for="mail_encryption">Encriptación</label>
                        <select id="mail_encryption" name="mail_encryption">
                            <option value="">Ninguna</option>
                            <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                    </div>

                    <div class="form-group smtp-field">
                        <label for="mail_username">Usuario SMTP *</label>
                        <input type="text" id="mail_username" name="mail_username" value="{{ old('mail_username', $settings['mail_username']) }}" placeholder="tu-email@gmail.com">
                    </div>

                    <div class="form-group smtp-field">
                        <label for="mail_password">Contraseña SMTP</label>
                        <input type="password" id="mail_password" name="mail_password" placeholder="Dejar vacío para mantener la actual">
                        <small>Dejar vacío para mantener la contraseña actual</small>
                    </div>
                </div>
            </div>

            <!-- Configuración del Remitente -->
            <div class="form-section">
                <div class="section-header">
                    <h3>👤 Configuración del Remitente</h3>
                    <p>Información que aparecerá como remitente en los emails</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="mail_from_address">Email del Remitente *</label>
                        <input type="email" id="mail_from_address" name="mail_from_address" value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="mail_from_name">Nombre del Remitente *</label>
                        <input type="text" id="mail_from_name" name="mail_from_name" value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required>
                    </div>
                </div>
            </div>

            <!-- Configuraciones de Notificaciones -->
            <div class="form-section">
                <div class="section-header">
                    <h3>🔔 Configuraciones de Notificaciones</h3>
                    <p>Controla qué tipos de emails se envían automáticamente</p>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <div class="checkbox-grid">
                            <div class="checkbox-item">
                                <input type="hidden" name="notification_emails" value="0">
                                <input type="checkbox" id="notification_emails" name="notification_emails" value="1" {{ old('notification_emails', $settings['notification_emails']) ? 'checked' : '' }}>
                                <label for="notification_emails">
                                    <span class="checkbox-title">Emails de Notificación</span>
                                    <span class="checkbox-desc">Enviar emails de notificación general a los usuarios</span>
                                </label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="welcome_emails" value="0">
                                <input type="checkbox" id="welcome_emails" name="welcome_emails" value="1" {{ old('welcome_emails', $settings['welcome_emails']) ? 'checked' : '' }}>
                                <label for="welcome_emails">
                                    <span class="checkbox-title">Emails de Bienvenida</span>
                                    <span class="checkbox-desc">Enviar email de bienvenida a nuevos usuarios registrados</span>
                                </label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="company_approval_emails" value="0">
                                <input type="checkbox" id="company_approval_emails" name="company_approval_emails" value="1" {{ old('company_approval_emails', $settings['company_approval_emails']) ? 'checked' : '' }}>
                                <label for="company_approval_emails">
                                    <span class="checkbox-title">Emails de Aprobación de Empresas</span>
                                    <span class="checkbox-desc">Enviar emails cuando se apruebe o rechace una empresa</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prueba de Configuración -->
            <div class="form-section">
                <div class="section-header">
                    <h3>🧪 Prueba de Configuración</h3>
                    <p>Envía un email de prueba para verificar la configuración</p>
                </div>

                <div class="test-email-section">
                    <div class="test-email-form">
                        <div class="form-group">
                            <label for="test_email">Email de Prueba</label>
                            <input type="email" id="test_email" placeholder="test@example.com">
                        </div>
                        <button type="button" onclick="sendTestEmail()" class="btn btn-test">
                            <i class="fas fa-paper-plane"></i>
                            Enviar Email de Prueba
                        </button>
                    </div>
                    <div id="test-result" class="test-result" style="display: none;"></div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Guardar Configuraciones
            </button>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i>
                Cancelar
            </a>
        </div>
    </form>
</div>

<style>
.settings-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 2rem;
}

.settings-header {
    margin-bottom: 2rem;
}

.header-content {
    text-align: center;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #667eea;
    text-decoration: none;
    margin-bottom: 1rem;
    font-weight: 500;
}

.back-btn:hover {
    color: #5a67d8;
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
    align-items: flex-start;
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

.alert ul {
    margin: 0;
    padding-left: 1rem;
}

.settings-form {
    background: white;
    border-radius: 16px;
    padding: 2rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    border: 1px solid #e5e7eb;
}

.form-sections {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-section {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 2rem;
}

.form-section:last-child {
    border-bottom: none;
    padding-bottom: 0;
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-header h3 {
    font-size: 1.25rem;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.section-header p {
    color: #6b7280;
    font-size: 0.875rem;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group.full-width {
    grid-column: 1 / -1;
}

.form-group label {
    margin-bottom: 0.5rem;
    color: #374151;
    font-weight: 500;
}

.form-group input,
.form-group select {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group small {
    margin-top: 0.25rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.checkbox-grid {
    display: grid;
    gap: 1rem;
}

.checkbox-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    transition: border-color 0.2s;
}

.checkbox-item:hover {
    border-color: #d1d5db;
}

.checkbox-item input[type="checkbox"] {
    margin-top: 0.25rem;
}

.checkbox-item label {
    flex: 1;
    margin: 0;
    cursor: pointer;
}

.checkbox-title {
    display: block;
    font-weight: 500;
    color: #374151;
    margin-bottom: 0.25rem;
}

.checkbox-desc {
    display: block;
    font-size: 0.875rem;
    color: #6b7280;
}

.test-email-section {
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 1.5rem;
}

.test-email-form {
    display: flex;
    gap: 1rem;
    align-items: end;
    margin-bottom: 1rem;
}

.test-email-form .form-group {
    flex: 1;
    margin: 0;
}

.test-result {
    padding: 1rem;
    border-radius: 8px;
    margin-top: 1rem;
}

.test-result.success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.test-result.error {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fca5a5;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #e5e7eb;
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

.btn-test {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.btn-test:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
}

@media (max-width: 768px) {
    .settings-container {
        padding: 1rem;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .test-email-form {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>

<script>
// Mostrar/ocultar campos SMTP según el driver seleccionado
document.getElementById('mail_driver').addEventListener('change', function() {
    const smtpFields = document.querySelectorAll('.smtp-field');
    const isSmtp = this.value === 'smtp';
    
    smtpFields.forEach(field => {
        field.style.display = isSmtp ? 'flex' : 'none';
        const input = field.querySelector('input, select');
        if (input) {
            input.required = isSmtp && input.hasAttribute('data-required');
        }
    });
});

// Ejecutar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('mail_driver').dispatchEvent(new Event('change'));
});

function sendTestEmail() {
    const email = document.getElementById('test_email').value;
    const resultDiv = document.getElementById('test-result');
    
    if (!email) {
        alert('Por favor ingresa un email para la prueba');
        return;
    }
    
    // Mostrar loading
    resultDiv.style.display = 'block';
    resultDiv.className = 'test-result';
    resultDiv.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enviando email de prueba...';
    
    // Simular envío de email de prueba
    fetch('{{ route("admin.settings.test-email") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.className = 'test-result success';
            resultDiv.innerHTML = '<i class="fas fa-check-circle"></i> Email de prueba enviado correctamente';
        } else {
            resultDiv.className = 'test-result error';
            resultDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error al enviar email: ' + (data.message || 'Error desconocido');
        }
    })
    .catch(error => {
        resultDiv.className = 'test-result error';
        resultDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Error de conexión al enviar email de prueba';
    });
}
</script>
@endsection