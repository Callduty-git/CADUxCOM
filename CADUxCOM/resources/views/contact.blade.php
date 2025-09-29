<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
</head>
<body>
    <div class="page-container">
    <x-header-pages />
    <div class="contact-container">
        <!-- Header Section -->
        <div class="contact-header">
            <button class="contact-btn">
                <i class="icon-phone">📞</i>
                Contáctanos
            </button>
            <h1>¿Necesitas ayuda?</h1>
            <p>Estamos aquí para resolver todas tus dudas y brindarte la mejor atención personalizada</p>
        </div>

        <!-- Main Content -->
        <div class="contact-content">
            <!-- Left Column - Contact Form -->
            <div class="contact-form-section">
                <div class="form-card">
                    <div class="form-header">
                        <i class="icon-envelope">✉️</i>
                        <h2>Envíanos un Mensaje</h2>
                        <p>Completa el formulario y te responderemos en menos de 24 horas</p>
                    </div>

                    <form method="POST" action="{{ route('contact.send') }}" class="contact-form">
                        @csrf
                        
                        @if ($errors->any())
                            <div class="error-messages">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="success-message">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="name">Nombre Completo</label>
                            <input type="text" id="name" name="name" placeholder="Tu nombre completo" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Correo Electrónico</label>
                            <input type="email" id="email" name="email" placeholder="tu@email.com" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="subject">Asunto</label>
                            <input type="text" id="subject" name="subject" placeholder="¿De qué se trata tu mensaje?" value="{{ old('subject') }}" required>
                        </div>

                        <div class="form-group">
                            <label for="message">Mensaje</label>
                            <textarea id="message" name="message" placeholder="Cuéntanos en detalle cómo podemos ayudarte..." rows="5" required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="submit-btn">
                            <i class="icon-send">✈️</i>
                            Enviar Mensaje
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column - Contact Info -->
            <div class="contact-info-section">
                <!-- Contact Information -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="icon-location">📍</i>
                        <h3>Información de Contacto</h3>
                    </div>
                    <div class="info-content">
                        <div class="info-item">
                            <img src="{{ asset('images/icon-user.png') }}" alt="Teléfono" class="info-icon">
                            <div>
                                <strong>+57 (1) 234-5678</strong>
                                <span>Lun - Vie: 9AM - 6PM</span>
                            </div>
                        </div>
                        <div class="info-item">
                            <img src="{{ asset('images/icon-user.png') }}" alt="Email" class="info-icon">
                            <div>
                                <strong>contacto@caduxcom.com</strong>
                                <span>Respuesta en 24h</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="icon-globe">🌐</i>
                        <h3>Síguenos</h3>
                    </div>
                    <div class="social-buttons">
                        <a href="#" class="social-btn facebook">
                            <img src="{{ asset('images/facebook.png') }}" alt="Facebook" class="social-icon">
                            Facebook
                        </a>
                        <a href="#" class="social-btn instagram">
                            <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="social-icon">
                            Instagram
                        </a>
                        <a href="#" class="social-btn tiktok">
                            <img src="{{ asset('images/tik-tok.png') }}" alt="TikTok" class="social-icon">
                            TikTok
                        </a>
                    </div>
                </div>

                <!-- Quick Contact -->
                <div class="info-card">
                    <div class="info-header">
                        <i class="icon-chat">💬</i>
                        <h3>Contacto Rápido</h3>
                        <p>¿Necesitas ayuda inmediata? Usa nuestros canales de atención rápida</p>
                    </div>
                    <div class="quick-contact">
                        <a href="tel:+5712345678" class="quick-btn call">
                            <i class="icon-phone">📞</i>
                            <div>
                                <strong>Llamar Ahora</strong>
                                <span>Atención inmediata</span>
                            </div>
                        </a>
                        <a href="mailto:contacto@caduxcom.com" class="quick-btn email">
                            <i class="icon-email">✉️</i>
                            <div>
                                <strong>Email Directo</strong>
                                <span>Respuesta en 24h</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-footer />
    </div>
</body>
</html>
