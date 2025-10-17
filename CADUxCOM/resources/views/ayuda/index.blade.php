<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centro de Ayuda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/header-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        /* Estilos específicos para las tarjetas interactivas */
        .quick-help-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .quick-help-item {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #333;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .quick-help-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #89CF6D, #49874E);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .quick-help-item:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(137, 207, 109, 0.3);
            border-color: #89CF6D;
        }

        .quick-help-item:hover::before {
            transform: scaleX(1);
        }

        .help-icon {
            width: 60px;
            height: 60px;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .quick-help-item:hover .help-icon {
            transform: scale(1.1);
        }

        .quick-help-item h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #49874E;
            transition: color 0.3s ease;
        }

        .quick-help-item:hover h3 {
            color: #89CF6D;
        }

        .quick-help-item p {
            color: #666;
            line-height: 1.6;
            margin: 0;
            transition: color 0.3s ease;
        }

        .quick-help-item:hover p {
            color: #555;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .quick-help-grid {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
            
            .quick-help-item {
                padding: 1.5rem;
            }
            
            .help-icon {
                width: 50px;
                height: 50px;
            }
            
            .quick-help-item h3 {
                font-size: 1.3rem;
            }
        }

        /* Animación de entrada */
        .quick-help-item {
            animation: fadeInUp 0.6s ease forwards;
            opacity: 0;
            transform: translateY(30px);
        }

        .quick-help-item:nth-child(1) { animation-delay: 0.1s; }
        .quick-help-item:nth-child(2) { animation-delay: 0.2s; }
        .quick-help-item:nth-child(3) { animation-delay: 0.3s; }
        .quick-help-item:nth-child(4) { animation-delay: 0.4s; }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />

        <div class="help-container">
            <!-- Header Section -->
            <div class="help-header">
                <h1>Centro de Ayuda</h1>
                <p>Encuentra respuestas rápidas a tus preguntas más frecuentes</p>
            </div>

            <!-- Search Section -->
            <div class="search-section">
                <div class="search-box">
                    <img src="{{ asset('images/lupa.png') }}" alt="Buscar" class="search-icon">
                    <input type="text" placeholder="¿En qué podemos ayudarte?" class="search-input">
                    <button class="search-btn">Buscar</button>
                </div>
            </div>

            <!-- Quick Help -->
            <div class="quick-help">
                <h2>Ayuda Rápida</h2>
                <div class="quick-help-grid">
                    <a href="{{ route('ayuda.mi-cuenta') }}" class="quick-help-item">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Cuenta" class="help-icon">
                        <h3>Mi Cuenta</h3>
                        <p>Gestiona tu perfil y configuraciones</p>
                    </a>
                    <a href="{{ route('ayuda.pedidos') }}" class="quick-help-item">
                        <img src="{{ asset('images/carrito-de-compras.png') }}" alt="Pedidos" class="help-icon">
                        <h3>Mis Pedidos</h3>
                        <p>Rastrea y gestiona tus compras</p>
                    </a>
                    <a href="{{ route('ayuda.pagos') }}" class="quick-help-item">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Pagos" class="help-icon">
                        <h3>Pagos</h3>
                        <p>Información sobre métodos de pago</p>
                    </a>
                    <a href="{{ route('ayuda.entrega') }}" class="quick-help-item">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Entrega" class="help-icon">
                        <h3>Entrega</h3>
                        <p>Información sobre envíos y entregas</p>
                    </a>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="faq-section">
                <h2>Preguntas Frecuentes</h2>
                <div class="faq-container">
                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Cómo funciona CADUxCOM?</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>CADUxCOM es una plataforma que conecta empresas con consumidores para ofrecer productos próximos a caducar a precios reducidos. Las empresas publican sus productos con descuentos y los usuarios pueden comprarlos, reduciendo el desperdicio de alimentos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Los productos están en buen estado?</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Sí, todos los productos están en perfecto estado para el consumo. Solo están próximos a su fecha de vencimiento, pero mantienen su calidad y frescura. Trabajamos con empresas confiables que garantizan la calidad de sus productos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Cómo puedo registrarme como empresa?</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Para registrarte como empresa, ve a la página de registro y selecciona la opción "Empresa". Necesitarás proporcionar información como NIT, certificado de cámara de comercio, dirección y datos de contacto. Nuestro equipo revisará tu solicitud.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Qué métodos de pago aceptan?</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Aceptamos tarjetas de crédito y débito, transferencias bancarias y billeteras digitales. Todos los pagos son seguros y procesados a través de plataformas certificadas.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Cómo funciona la entrega?</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>CADUxCOM es una plataforma de conexión. Después de tu compra, recibirás la información de contacto de la empresa vendedora para coordinar directamente el envío de tus productos. Cada empresa maneja sus propios tiempos, métodos y costos de entrega.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h3>¿Puedo cancelar mi pedido?</h3>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Sí, puedes cancelar tu pedido antes de que sea procesado. Una vez que el pedido esté en preparación, no se podrá cancelar. Para cancelaciones, contacta a nuestro servicio al cliente.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Support -->
            <div class="contact-support">
                <h2>¿No encuentras lo que buscas?</h2>
                <p>Nuestro equipo de soporte está aquí para ayudarte</p>
                <div class="support-options">
                    <a href="{{ route('contact.index') }}" class="support-btn primary">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Contacto" class="support-icon">
                        Contactar Soporte
                    </a>
                    <a href="mailto:caduxcom.store@gmail.com" class="support-btn secondary">
                        <img src="{{ asset('images/icon-user.png') }}" alt="Email" class="support-icon">
                        Enviar Email
                    </a>
                </div>
            </div>
        </div>

        <x-footer />
    </div>

    <script>
        // FAQ Toggle functionality
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const faqItem = question.parentElement;
                const answer = faqItem.querySelector('.faq-answer');
                const toggle = question.querySelector('.faq-toggle');
                
                // Close other open FAQs
                document.querySelectorAll('.faq-item').forEach(item => {
                    if (item !== faqItem) {
                        item.classList.remove('active');
                        item.querySelector('.faq-toggle').textContent = '+';
                    }
                });
                
                // Toggle current FAQ
                faqItem.classList.toggle('active');
                toggle.textContent = faqItem.classList.contains('active') ? '−' : '+';
            });
        });

        // Search functionality
        document.querySelector('.search-btn').addEventListener('click', () => {
            const searchTerm = document.querySelector('.search-input').value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question h3').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    item.scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>