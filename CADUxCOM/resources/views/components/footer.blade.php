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
                @if(!Auth::guard('empresa')->check())
                    <li><a href="{{ url('/') }}">Inicio</a></li>
                    <li><a href="{{ url('/productos') }}">Productos</a></li>
                @endif
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

        <!-- Información de Contacto -->
        <div class="footer-section">
            <h3 class="footer-title">Contacto</h3>
            <div class="contact-info">
                <p><strong>📧 Email:</strong> caduxcom.store@gmail.com</p>
                <p><strong>📞 Teléfono:</strong> 3233526807</p>
            </div>
            
            <h3 class="footer-title" style="margin-top: 20px;">Síguenos</h3>
            <div class="social-icons">
                <a href="https://www.facebook.com/share/14FJfgWzoRf/" target="_blank">
                    <img src="{{ asset('images/facebook.png') }}" alt="Facebook" class="social-icon">
                </a>
                <a href="https://www.instagram.com/caduxcom?igsh=MWxwa2t0NmJoOWtubA==" target="_blank">
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
