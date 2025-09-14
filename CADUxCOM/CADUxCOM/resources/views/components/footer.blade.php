<footer class="footer">
    <div class="footer-container">
        
        <!-- Logo -->
        <div class="footer-section logo-section">
            <img src="{{ asset('images/logocort-caduxcom.png') }}" alt="CADUxCOM Logo" class="footer-logo">
            <p class="footer-tagline">Calidad, confianza y compromiso con la sostenibilidad.</p>
        </div>

        <!-- Enlaces Rápidos -->
        <div class="footer-section">
            <h3 class="footer-title">Enlaces Rápidos</h3>
            <ul class="footer-links">
                <li><a href="{{ url('/') }}">Inicio</a></li>
                <li><a href="{{ url('/productos') }}">Productos</a></li>
                <li><a href="{{ route('about') }}">Nosotros</a></li>
                <li><a href="{{ route('contact.index') }}">Contacto</a></li>
            </ul>
        </div>

        <!-- Soporte -->
        <div class="footer-section">
            <h3 class="footer-title">Soporte</h3>
            <ul class="footer-links">
                <li><a href="{{ route('help') }}">Centro de Ayuda</a></li>
                <li><a href="{{ route('terms') }}">Términos de Servicio</a></li>
                <li><a href="{{ route('privacy') }}">Política de Privacidad</a></li>
            </ul>
        </div>

        <!-- Redes Sociales -->
        <div class="footer-section">
            <h3 class="footer-title">Síguenos</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com" target="_blank">
                    <img src="{{ asset('images/facebook.png') }}" alt="Facebook" class="social-icon">
                </a>
                <a href="https://www.instagram.com" target="_blank">
                    <img src="{{ asset('images/instagram.png') }}" alt="Instagram" class="social-icon">
                </a>
                <a href="https://www.tiktok.com" target="_blank">
                    <img src="{{ asset('images/tik-tok.png') }}" alt="TikTok" class="social-icon">
                </a>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="footer-bottom">
        <p>© 2025 CADUxCOM. Todos los derechos reservados.</p>
    </div>
</footer>
