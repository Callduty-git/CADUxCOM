@extends('layouts.admin')

@section('title', 'Configuraciones Generales')

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <div class="header-content">
            <a href="{{ route('admin.settings.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver a Configuraciones
            </a>
            <h1>⚙️ Configuraciones Generales</h1>
            <p>Configura la información básica y configuraciones principales de la aplicación</p>
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

    <form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" class="settings-form">
        @csrf
        @method('PUT')

        <div class="form-sections">
            <!-- Información de la Aplicación -->
            <div class="form-section">
                <div class="section-header">
                    <h3>📱 Información de la Aplicación</h3>
                    <p>Configuraciones básicas de identidad de la aplicación</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="app_name">Nombre de la Aplicación *</label>
                        <input type="text" id="app_name" name="app_name" value="{{ old('app_name', $settings['app_name']) }}" required>
                    </div>

                    <div class="form-group full-width">
                        <label for="app_description">Descripción de la Aplicación</label>
                        <textarea id="app_description" name="app_description" rows="3">{{ old('app_description', $settings['app_description']) }}</textarea>
                    </div>

                    <div class="form-group full-width">
                        <label for="app_keywords">Palabras Clave (SEO)</label>
                        <input type="text" id="app_keywords" name="app_keywords" value="{{ old('app_keywords', $settings['app_keywords']) }}" placeholder="empresa, productos, servicios">
                        <small>Separadas por comas</small>
                    </div>
                </div>
            </div>

            <!-- Información de Contacto -->
            <div class="form-section">
                <div class="section-header">
                    <h3>📞 Información de Contacto</h3>
                    <p>Datos de contacto que se mostrarán en la aplicación</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="contact_email">Email de Contacto *</label>
                        <input type="email" id="contact_email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">Teléfono de Contacto</label>
                        <input type="text" id="contact_phone" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                    </div>

                    <div class="form-group full-width">
                        <label for="contact_address">Dirección de Contacto</label>
                        <input type="text" id="contact_address" name="contact_address" value="{{ old('contact_address', $settings['contact_address']) }}">
                    </div>
                </div>
            </div>

            <!-- Configuraciones de Funcionamiento -->
            <div class="form-section">
                <div class="section-header">
                    <h3>🔧 Configuraciones de Funcionamiento</h3>
                    <p>Configuraciones que afectan el comportamiento de la aplicación</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="max_products_per_company">Máximo de Productos por Empresa *</label>
                        <input type="number" id="max_products_per_company" name="max_products_per_company" value="{{ old('max_products_per_company', $settings['max_products_per_company']) }}" min="1" max="1000" required>
                    </div>

                    <div class="form-group full-width">
                        <div class="checkbox-grid">
                            <div class="checkbox-item">
                                <input type="hidden" name="maintenance_mode" value="0">
                                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" value="1" {{ old('maintenance_mode', $settings['maintenance_mode']) ? 'checked' : '' }}>
                                <label for="maintenance_mode">
                                    <span class="checkbox-title">Modo de Mantenimiento</span>
                                    <span class="checkbox-desc">Activar para mostrar página de mantenimiento</span>
                                </label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="registration_enabled" value="0">
                                <input type="checkbox" id="registration_enabled" name="registration_enabled" value="1" {{ old('registration_enabled', $settings['registration_enabled']) ? 'checked' : '' }}>
                                <label for="registration_enabled">
                                    <span class="checkbox-title">Registro de Usuarios Habilitado</span>
                                    <span class="checkbox-desc">Permitir que nuevos usuarios se registren</span>
                                </label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="company_registration_enabled" value="0">
                                <input type="checkbox" id="company_registration_enabled" name="company_registration_enabled" value="1" {{ old('company_registration_enabled', $settings['company_registration_enabled']) ? 'checked' : '' }}>
                                <label for="company_registration_enabled">
                                    <span class="checkbox-title">Registro de Empresas Habilitado</span>
                                    <span class="checkbox-desc">Permitir que nuevas empresas se registren</span>
                                </label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="auto_approve_companies" value="0">
                                <input type="checkbox" id="auto_approve_companies" name="auto_approve_companies" value="1" {{ old('auto_approve_companies', $settings['auto_approve_companies']) ? 'checked' : '' }}>
                                <label for="auto_approve_companies">
                                    <span class="checkbox-title">Auto-aprobar Empresas</span>
                                    <span class="checkbox-desc">Aprobar automáticamente nuevas empresas sin revisión manual</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Archivos de Imagen -->
            <div class="form-section">
                <div class="section-header">
                    <h3>🖼️ Archivos de Imagen</h3>
                    <p>Logo y favicon de la aplicación</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="logo">Logo de la Aplicación</label>
                        <input type="file" id="logo" name="logo" accept="image/*">
                        <small>Formatos: JPEG, PNG, JPG, GIF, SVG. Máximo 2MB</small>
                        @if($settings['logo'])
                            <div class="current-image">
                                <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo actual" style="max-width: 200px; margin-top: 10px;">
                                <p>Logo actual</p>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="favicon">Favicon</label>
                        <input type="file" id="favicon" name="favicon" accept=".ico,.png">
                        <small>Formatos: ICO, PNG. Máximo 512KB</small>
                        @if($settings['favicon'])
                            <div class="current-image">
                                <img src="{{ asset('storage/' . $settings['favicon']) }}" alt="Favicon actual" style="max-width: 32px; margin-top: 10px;">
                                <p>Favicon actual</p>
                            </div>
                        @endif
                    </div>
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
.form-group textarea {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
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

.current-image {
    margin-top: 0.5rem;
    padding: 0.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    text-align: center;
}

.current-image p {
    margin: 0.5rem 0 0 0;
    font-size: 0.875rem;
    color: #6b7280;
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
}
</style>
@endsection