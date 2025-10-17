@extends('layouts.admin')

@section('title', 'Configuraciones de Plataforma')

@section('content')
<div class="settings-container">
    <div class="settings-header">
        <div class="header-content">
            <a href="{{ route('admin.settings.index') }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                Volver a Configuraciones
            </a>
            <h1>🌐 Configuraciones de Plataforma</h1>
            <p>Configura el comportamiento y límites específicos de la plataforma</p>
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

    <form action="{{ route('admin.settings.platform.update') }}" method="POST" class="settings-form">
        @csrf
        @method('PUT')

        <div class="form-sections">
            <!-- Sistema de Reseñas -->
            <div class="form-section">
                <div class="section-header">
                    <h3>⭐ Sistema de Reseñas</h3>
                    <p>Configuraciones relacionadas con el sistema de comentarios y reseñas</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="max_reviews_per_user">Máximo de Reseñas por Usuario *</label>
                        <input type="number" id="max_reviews_per_user" name="max_reviews_per_user" value="{{ old('max_reviews_per_user', $settings['max_reviews_per_user']) }}" min="1" max="100" required>
                        <small>Número máximo de reseñas que un usuario puede escribir</small>
                    </div>

                    <div class="form-group">
                        <label for="min_review_length">Longitud Mínima de Reseña *</label>
                        <input type="number" id="min_review_length" name="min_review_length" value="{{ old('min_review_length', $settings['min_review_length']) }}" min="10" max="500" required>
                        <small>Número mínimo de caracteres para una reseña</small>
                    </div>

                    <div class="form-group">
                        <label for="max_review_length">Longitud Máxima de Reseña *</label>
                        <input type="number" id="max_review_length" name="max_review_length" value="{{ old('max_review_length', $settings['max_review_length']) }}" min="50" max="2000" required>
                        <small>Número máximo de caracteres para una reseña</small>
                    </div>

                    <div class="form-group full-width">
                        <div class="checkbox-grid">
                            <div class="checkbox-item">
                                <input type="hidden" name="reviews_enabled" value="0">
                                <input type="checkbox" id="reviews_enabled" name="reviews_enabled" value="1" {{ old('reviews_enabled', $settings['reviews_enabled']) ? 'checked' : '' }}>
                                <label for="reviews_enabled">
                                    <span class="checkbox-title">Sistema de Reseñas Habilitado</span>
                                    <span class="checkbox-desc">Permitir que los usuarios escriban y vean reseñas</span>
                                </label>
                            </div>

                            <div class="checkbox-item">
                                <input type="hidden" name="reviews_require_approval" value="0">
                                <input type="checkbox" id="reviews_require_approval" name="reviews_require_approval" value="1" {{ old('reviews_require_approval', $settings['reviews_require_approval']) ? 'checked' : '' }}>
                                <label for="reviews_require_approval">
                                    <span class="checkbox-title">Reseñas Requieren Aprobación</span>
                                    <span class="checkbox-desc">Las reseñas deben ser aprobadas por un administrador antes de mostrarse</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuraciones de Búsqueda y Visualización -->
            <div class="form-section">
                <div class="section-header">
                    <h3>🔍 Búsqueda y Visualización</h3>
                    <p>Configuraciones que afectan la búsqueda y visualización de contenido</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="search_results_per_page">Resultados por Página *</label>
                        <input type="number" id="search_results_per_page" name="search_results_per_page" value="{{ old('search_results_per_page', $settings['search_results_per_page']) }}" min="5" max="100" required>
                        <small>Número de resultados mostrados por página en búsquedas</small>
                    </div>

                    <div class="form-group">
                        <label for="featured_companies_limit">Límite de Empresas Destacadas *</label>
                        <input type="number" id="featured_companies_limit" name="featured_companies_limit" value="{{ old('featured_companies_limit', $settings['featured_companies_limit']) }}" min="1" max="50" required>
                        <small>Número máximo de empresas destacadas en la página principal</small>
                    </div>
                </div>
            </div>

            <!-- Configuraciones de Rendimiento -->
            <div class="form-section">
                <div class="section-header">
                    <h3>⚡ Rendimiento y Cache</h3>
                    <p>Configuraciones que afectan el rendimiento y velocidad de la aplicación</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="cache_duration">Duración del Cache (minutos) *</label>
                        <input type="number" id="cache_duration" name="cache_duration" value="{{ old('cache_duration', $settings['cache_duration']) }}" min="1" max="1440" required>
                        <small>Tiempo en minutos que se mantienen los datos en cache</small>
                    </div>

                    <div class="form-group">
                        <label for="session_lifetime">Duración de Sesión (minutos) *</label>
                        <input type="number" id="session_lifetime" name="session_lifetime" value="{{ old('session_lifetime', $settings['session_lifetime']) }}" min="60" max="10080" required>
                        <small>Tiempo en minutos antes de que expire una sesión de usuario</small>
                    </div>

                    <div class="form-group">
                        <label for="password_reset_expire">Expiración de Reset de Contraseña (minutos) *</label>
                        <input type="number" id="password_reset_expire" name="password_reset_expire" value="{{ old('password_reset_expire', $settings['password_reset_expire']) }}" min="15" max="1440" required>
                        <small>Tiempo en minutos antes de que expire un enlace de reset de contraseña</small>
                    </div>
                </div>
            </div>

            <!-- Información del Sistema -->
            <div class="form-section">
                <div class="section-header">
                    <h3>📊 Información del Sistema</h3>
                    <p>Información actual del sistema y estadísticas</p>
                </div>

                <div class="system-info-grid">
                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-server"></i>
                        </div>
                        <div class="info-content">
                            <h4>Versión de PHP</h4>
                            <p>{{ PHP_VERSION }}</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-code-branch"></i>
                        </div>
                        <div class="info-content">
                            <h4>Versión de Laravel</h4>
                            <p>{{ app()->version() }}</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-database"></i>
                        </div>
                        <div class="info-content">
                            <h4>Base de Datos</h4>
                            <p>{{ config('database.default') }}</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-memory"></i>
                        </div>
                        <div class="info-content">
                            <h4>Límite de Memoria</h4>
                            <p>{{ ini_get('memory_limit') }}</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="info-content">
                            <h4>Tiempo Máximo de Ejecución</h4>
                            <p>{{ ini_get('max_execution_time') }}s</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-icon">
                            <i class="fas fa-upload"></i>
                        </div>
                        <div class="info-content">
                            <h4>Tamaño Máximo de Subida</h4>
                            <p>{{ ini_get('upload_max_filesize') }}</p>
                        </div>
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

.form-group input {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
}

.form-group input:focus {
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

.system-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8fafc;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
}

.info-icon {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.info-content h4 {
    margin: 0 0 0.25rem 0;
    font-size: 0.875rem;
    color: #374151;
    font-weight: 500;
}

.info-content p {
    margin: 0;
    font-size: 1rem;
    color: #1f2937;
    font-weight: 600;
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
    
    .system-info-grid {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
}
</style>
@endsection