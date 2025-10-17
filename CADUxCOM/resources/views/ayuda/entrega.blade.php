<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Entrega - Centro de Ayuda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/header-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .delivery-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .delivery-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .delivery-header h1 {
            color: #49874E;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .delivery-header p {
            color: #666;
            font-size: 1.1rem;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            color: #49874E;
            text-decoration: none;
            font-weight: 500;
            margin-bottom: 2rem;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #89CF6D;
        }

        .back-link::before {
            content: '←';
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }

        .delivery-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .delivery-option {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #89CF6D;
            transition: transform 0.3s ease;
        }

        .delivery-option:hover {
            transform: translateY(-5px);
        }

        .delivery-option h3 {
            color: #49874E;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .delivery-icon {
            width: 30px;
            height: 30px;
            margin-right: 0.5rem;
        }

        .delivery-option p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .delivery-features {
            list-style: none;
            padding: 0;
        }

        .delivery-features li {
            padding: 0.25rem 0;
            color: #555;
            font-size: 0.9rem;
        }

        .delivery-features li::before {
            content: '✓';
            color: #89CF6D;
            font-weight: bold;
            margin-right: 0.5rem;
        }

        .delivery-time {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            margin-top: 1rem;
            text-align: center;
        }

        .delivery-time strong {
            color: #49874E;
            font-size: 1.1rem;
        }

        .info-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .info-section h3 {
            color: #49874E;
            font-size: 1.3rem;
            margin-bottom: 1rem;
        }

        .info-section p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .tracking-steps {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .tracking-step {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: #89CF6D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            margin: 0 auto 1rem;
        }

        .tracking-step h4 {
            color: #49874E;
            margin-bottom: 0.5rem;
        }

        .tracking-step p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .coverage-map {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .coverage-map h3 {
            color: #49874E;
            text-align: center;
            margin-bottom: 2rem;
        }

        .cities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .city-item {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 1rem;
            text-align: center;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .city-item:hover {
            border-color: #89CF6D;
            background: #FFFFFF;
        }

        .city-item h4 {
            color: #49874E;
            margin-bottom: 0.5rem;
        }

        .city-item p {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        .faq-section {
            margin-top: 3rem;
        }

        .faq-section h3 {
            color: #49874E;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .faq-item {
            background: #FFFFFF;
            border-radius: 10px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            transition: background-color 0.3s ease;
        }

        .faq-question:hover {
            background: #e9ecef;
        }

        .faq-question h4 {
            color: #49874E;
            margin: 0;
            font-size: 1.1rem;
        }

        .faq-toggle {
            color: #89CF6D;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 1.5rem;
            max-height: 200px;
        }

        .faq-answer p {
            color: #666;
            line-height: 1.6;
            margin: 0;
        }

        .contact-support {
            background: linear-gradient(135deg, #89CF6D, #49874E);
            color: white;
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            margin-top: 3rem;
        }

        .contact-support h3 {
            margin-bottom: 1rem;
        }

        .contact-support p {
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .support-btn {
            background: white;
            color: #49874E;
            padding: 0.75rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-block;
            margin: 0 0.5rem;
        }

        .support-btn:hover {
            background: #f8f9fa;
            transform: translateY(-2px);
        }

        .tracking-simulator {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .tracking-simulator h3 {
            color: #49874E;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .tracking-input {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            align-items: center;
            justify-content: center;
        }

        .tracking-input input {
            padding: 0.75rem;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            font-size: 1rem;
            width: 250px;
        }

        .tracking-input input:focus {
            outline: none;
            border-color: #89CF6D;
        }

        .track-btn {
            background: #89CF6D;
            color: white;
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .track-btn:hover {
            background: #49874E;
        }

        @media (max-width: 768px) {
            .delivery-container {
                padding: 1rem;
            }
            
            .delivery-header h1 {
                font-size: 2rem;
            }
            
            .delivery-options {
                grid-template-columns: 1fr;
            }
            
            .tracking-steps {
                grid-template-columns: 1fr;
            }
            
            .cities-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            }
            
            .support-btn {
                display: block;
                margin: 0.5rem 0;
            }

            .tracking-input {
                flex-direction: column;
            }

            .tracking-input input {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />

        <div class="help-container">
            <div class="delivery-container">
                <a href="{{ route('help') }}" class="back-link">Volver al Centro de Ayuda</a>
                
                <div class="delivery-header">
                    <h1>Información de Entrega</h1>
                    <p>CADUxCOM es una plataforma de conexión entre compradores y empresas. Los envíos son gestionados directamente por cada empresa vendedora.</p>
                </div>

                <!-- Opciones de entrega -->
                <div class="delivery-options">
                    <div class="delivery-option">
                        <h3>
                            <div class="delivery-icon">🤝</div>
                            Contacto Directo con la Empresa
                        </h3>
                        <p>Después de realizar tu compra, contacta directamente con la empresa vendedora para coordinar el envío.</p>
                        <ul class="delivery-features">
                            <li>Información de contacto disponible en tu pedido</li>
                            <li>Coordina directamente tiempos y métodos de entrega</li>
                            <li>Negocia costos de envío según tu ubicación</li>
                            <li>Seguimiento directo con la empresa</li>
                        </ul>
                        <div class="delivery-time">
                            <strong>Según acuerdo con la empresa</strong>
                        </div>
                    </div>

                    <div class="delivery-option">
                        <h3>
                            <div class="delivery-icon">📋</div>
                            Proceso de Compra
                        </h3>
                        <p>Así funciona el proceso de compra y entrega en CADUxCOM.</p>
                        <ul class="delivery-features">
                            <li>Realiza tu compra en la plataforma</li>
                            <li>Recibe la información de contacto de la empresa</li>
                            <li>Contacta directamente a la empresa vendedora</li>
                            <li>Coordina el envío según tus necesidades</li>
                        </ul>
                        <div class="delivery-time">
                            <strong>Inmediato después de la compra</strong>
                        </div>
                    </div>

                    <div class="delivery-option">
                        <h3>
                            <div class="delivery-icon">🏪</div>
                            Recogida en Tienda
                        </h3>
                        <p>Recoge tu pedido en nuestros puntos de venta.</p>
                        <ul class="delivery-features">
                            <li>Sin costo de envío</li>
                            <li>Horarios flexibles</li>
                            <li>Verificación en persona</li>
                            <li>Disponible en 24-48 horas</li>
                        </ul>
                        <div class="delivery-time">
                            <strong>Disponible en 1-2 días</strong>
                        </div>
                    </div>

                    <div class="delivery-option">
                        <h3>
                            <div class="delivery-icon">📦</div>
                            Punto de Recogida
                        </h3>
                        <p>Recoge en puntos autorizados cerca de ti.</p>
                        <ul class="delivery-features">
                            <li>Red de puntos aliados</li>
                            <li>Horarios extendidos</li>
                            <li>Almacenamiento seguro</li>
                            <li>Costo reducido</li>
                        </ul>
                        <div class="delivery-time">
                            <strong>2-4 días hábiles</strong>
                        </div>
                    </div>
                </div>



                <!-- Información importante sobre seguimiento -->
                <div class="info-section">
                    <div style="background-color: #f3e8ff; border: 1px solid #d8b4fe; border-radius: 8px; padding: 1rem; margin-bottom: 1rem;">
                        <div style="display: flex; align-items: flex-start;">
                            <div style="flex-shrink: 0; margin-right: 0.75rem;">
                                <svg style="width: 20px; height: 20px; color: #AA5FC7; margin-top: 2px;" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 style="font-size: 0.875rem; font-weight: 500; color: #8B4A9F; margin-bottom: 0.5rem;">Información importante</h4>
                                <div style="font-size: 0.875rem; color: #7c3aed;">
                                    <p><strong>CADUxCOM es una plataforma de conexión.</strong> Después de tu compra, recibirás la información de contacto de la empresa vendedora para coordinar directamente el seguimiento y entrega de tus productos.</p>
                                    <p style="margin-top: 0.5rem;">Cada empresa tiene sus propios procesos de seguimiento y entrega. Te recomendamos contactar directamente al vendedor para obtener información actualizada sobre tu pedido.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <!-- Información adicional -->
                <div class="info-section">
                    <h3>📋 Información Importante</h3>
                    <p><strong>Modelo de negocio:</strong> CADUxCOM es una plataforma de conexión. No manejamos envíos directamente.</p>
                    <p><strong>Después de comprar:</strong> Recibirás la información de contacto de la empresa vendedora para coordinar el envío.</p>
                    <p><strong>Responsabilidad del envío:</strong> Cada empresa es responsable de gestionar sus propios envíos y políticas de entrega.</p>
                    <p><strong>Coordinación directa:</strong> Contacta directamente con la empresa para acordar métodos, tiempos y costos de envío.</p>
                </div>

                <!-- Preguntas frecuentes sobre entrega -->
                <div class="faq-section">
                    <h3>Preguntas Frecuentes sobre Entrega</h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Cómo obtengo la información de contacto de la empresa?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Después de completar tu compra, recibirás un email con la información de contacto de la empresa vendedora. También puedes encontrar esta información en tu cuenta de usuario en la sección "Mis Pedidos".</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Qué debo coordinar con la empresa vendedora?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Debes coordinar con la empresa: método de envío, dirección de entrega, horarios disponibles, costos de envío y forma de pago del envío. Cada empresa tiene sus propias políticas y procedimientos.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Puedo cambiar la dirección de entrega después de realizar el pedido?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Los cambios de dirección deben coordinarse directamente con la empresa vendedora. Cada empresa tiene sus propias políticas sobre modificaciones de pedidos y direcciones de entrega.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Hay costo adicional por la entrega?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Los costos de envío son determinados y cobrados directamente por cada empresa vendedora. CADUxCOM no maneja ni cobra costos de envío. Debes coordinar estos costos directamente con la empresa después de realizar tu compra.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Qué debo hacer si mi pedido llega dañado?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Si tu pedido llega dañado, contacta inmediatamente a la empresa vendedora responsable del envío. Cada empresa tiene sus propias políticas de garantía y reemplazo. CADUxCOM puede mediar en caso de disputas entre comprador y vendedor.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Puedo programar una hora específica para la entrega?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>La programación de horarios de entrega debe coordinarse directamente con la empresa vendedora. Cada empresa tiene sus propios horarios y disponibilidad para entregas según su ubicación y capacidad logística.</p>
                        </div>
                    </div>
                </div>

                <!-- Contacto para soporte -->
                <div class="contact-support">
                    <h3>¿Problemas con tu entrega?</h3>
                    <p>Nuestro equipo de logística está disponible para resolver cualquier inconveniente</p>
                    <a href="{{ route('contact.index') }}" class="support-btn">Contactar Soporte</a>
                    <a href="mailto:caduxcom.store@gmail.com" class="support-btn">Enviar Email</a>
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

        // Tracking functionality (placeholder)
        function trackOrder() {
            const trackingNumber = document.getElementById('trackingNumber').value;
            if (trackingNumber.trim() === '') {
                alert('Por favor, ingresa un número de seguimiento válido.');
                return;
            }
            
            // Placeholder functionality - in a real app, this would make an API call
            alert(`Buscando información para el pedido: ${trackingNumber}\n\nEsta es una funcionalidad de demostración. En la aplicación real, esto mostraría el estado actual del pedido.`);
        }

        // Enter key support for tracking input
        document.getElementById('trackingNumber').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                trackOrder();
            }
        });
    </script>
</body>
</html>