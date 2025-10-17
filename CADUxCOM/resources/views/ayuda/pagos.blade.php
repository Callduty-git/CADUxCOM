<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información de Pagos - Centro de Ayuda - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}?v={{ time() }}">
    <link rel="stylesheet" href="{{ asset('css/header-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <style>
        .payments-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
        }

        .payments-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .payments-header h1 {
            color: #49874E;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .payments-header p {
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

        .payment-methods {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .payment-method {
            background: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border-left: 4px solid #89CF6D;
            transition: transform 0.3s ease;
        }

        .payment-method:hover {
            transform: translateY(-5px);
        }

        .payment-method h3 {
            color: #49874E;
            font-size: 1.3rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .payment-icon {
            width: 30px;
            height: 30px;
            margin-right: 0.5rem;
        }

        .payment-method p {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1rem;
        }

        .payment-features {
            list-style: none;
            padding: 0;
        }

        .payment-features li {
            padding: 0.25rem 0;
            color: #555;
            font-size: 0.9rem;
        }

        .payment-features li::before {
            content: '✓';
            color: #89CF6D;
            font-weight: bold;
            margin-right: 0.5rem;
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

        .security-features {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .security-feature {
            background: #FFFFFF;
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .security-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 1rem;
            background: #89CF6D;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .security-feature h4 {
            color: #49874E;
            margin-bottom: 0.5rem;
        }

        .security-feature p {
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

        @media (max-width: 768px) {
            .payments-container {
                padding: 1rem;
            }
            
            .payments-header h1 {
                font-size: 2rem;
            }
            
            .payment-methods {
                grid-template-columns: 1fr;
            }
            
            .security-features {
                grid-template-columns: 1fr;
            }
            
            .support-btn {
                display: block;
                margin: 0.5rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <x-header-pages />

        <div class="help-container">
            <div class="payments-container">
                <a href="{{ route('help') }}" class="back-link">Volver al Centro de Ayuda</a>
                
                <div class="payments-header">
                    <h1>Información de Pagos</h1>
                    <p>Todo lo que necesitas saber sobre métodos de pago y seguridad</p>
                </div>

                <!-- Métodos de pago disponibles -->
                <div class="payment-methods">
                    <div class="payment-method">
                        <h3>
                            <div class="payment-icon">💳</div>
                            Tarjetas de Crédito y Débito
                        </h3>
                        <p>Acepta todas las tarjetas principales con la máxima seguridad.</p>
                        <ul class="payment-features">
                            <li>Visa, MasterCard, American Express</li>
                            <li>Procesamiento instantáneo</li>
                            <li>Encriptación SSL 256-bit</li>
                            <li>Verificación 3D Secure</li>
                        </ul>
                    </div>

                    <div class="payment-method">
                        <h3>
                            <div class="payment-icon">🏦</div>
                            Transferencia Bancaria
                        </h3>
                        <p>Transfiere directamente desde tu cuenta bancaria.</p>
                        <ul class="payment-features">
                            <li>Todos los bancos colombianos</li>
                            <li>PSE (Pagos Seguros en Línea)</li>
                            <li>Confirmación automática</li>
                            <li>Sin comisiones adicionales</li>
                        </ul>
                    </div>



                    <div class="payment-method">
                        <h3>
                            <div class="payment-icon">📱</div>
                            Billeteras Digitales
                        </h3>
                        <p>Usa tu billetera digital favorita para pagar.</p>
                        <ul class="payment-features">
                            <li>Nequi, Daviplata, Tpaga</li>
                            <li>Pago con código QR</li>
                            <li>Confirmación inmediata</li>
                            <li>Historial de transacciones</li>
                        </ul>
                    </div>
                </div>

                <!-- Información de seguridad -->
                <div class="info-section">
                    <h3>🔒 Seguridad en los Pagos</h3>
                    <p>En CADUxCOM, la seguridad de tus datos financieros es nuestra prioridad. Utilizamos las tecnologías más avanzadas para proteger tu información.</p>
                    
                    <div class="security-features">
                        <div class="security-feature">
                            <div class="security-icon">🛡️</div>
                            <h4>Encriptación SSL</h4>
                            <p>Todos los datos se transmiten con encriptación de 256 bits</p>
                        </div>
                        <div class="security-feature">
                            <div class="security-icon">🔐</div>
                            <h4>PCI DSS Compliant</h4>
                            <p>Cumplimos con los estándares internacionales de seguridad</p>
                        </div>
                        <div class="security-feature">
                            <div class="security-icon">👁️</div>
                            <h4>Monitoreo 24/7</h4>
                            <p>Supervisión constante para detectar actividades sospechosas</p>
                        </div>
                        <div class="security-feature">
                            <div class="security-icon">🔄</div>
                            <h4>Tokenización</h4>
                            <p>Los datos de tarjetas se convierten en tokens seguros</p>
                        </div>
                    </div>
                </div>

                <!-- Proceso de pago -->
                <div class="info-section">
                    <h3>📋 Proceso de Pago</h3>
                    <p>Nuestro proceso de pago es simple y seguro:</p>
                    <ol style="color: #666; line-height: 1.8; padding-left: 1.5rem;">
                        <li><strong>Selecciona tus productos</strong> y agrégalos al carrito</li>
                        <li><strong>Revisa tu pedido</strong> y confirma los detalles</li>
                        <li><strong>Elige tu método de pago</strong> preferido</li>
                        <li><strong>Ingresa la información</strong> de pago de forma segura</li>
                        <li><strong>Confirma la transacción</strong> y recibe tu comprobante</li>
                        <li><strong>Recibe confirmación</strong> por email y SMS</li>
                    </ol>
                </div>

                <!-- Preguntas frecuentes sobre pagos -->
                <div class="faq-section">
                    <h3>Preguntas Frecuentes sobre Pagos</h3>
                    
                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Es seguro pagar con tarjeta en CADUxCOM?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Sí, es completamente seguro. Utilizamos encriptación SSL de 256 bits y cumplimos con los estándares PCI DSS. Nunca almacenamos los datos completos de tu tarjeta en nuestros servidores.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Puedo cambiar el método de pago después de realizar el pedido?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Una vez confirmado el pago, no es posible cambiar el método. Sin embargo, si el pago aún está pendiente, puedes contactar a nuestro servicio al cliente para asistencia.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Hay cargos adicionales por usar ciertos métodos de pago?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>No cobramos comisiones adicionales por ningún método de pago. El precio que ves es el precio final que pagarás.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Cuánto tiempo tarda en procesarse el pago?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Los pagos con tarjeta y billeteras digitales se procesan instantáneamente. Las transferencias bancarias pueden tardar entre 1-2 horas hábiles en confirmarse.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Qué hago si mi pago fue rechazado?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Verifica que los datos de tu tarjeta sean correctos y que tengas fondos suficientes. Si el problema persiste, contacta a tu banco o prueba con otro método de pago.</p>
                        </div>
                    </div>

                    <div class="faq-item">
                        <div class="faq-question">
                            <h4>¿Puedo obtener un reembolso?</h4>
                            <span class="faq-toggle">+</span>
                        </div>
                        <div class="faq-answer">
                            <p>Sí, ofrecemos reembolsos según nuestras políticas. Los reembolsos se procesan al método de pago original y pueden tardar 3-5 días hábiles en reflejarse.</p>
                        </div>
                    </div>
                </div>

                <!-- Contacto para soporte -->
                <div class="contact-support">
                    <h3>¿Necesitas ayuda con tu pago?</h3>
                    <p>Nuestro equipo de soporte está disponible para ayudarte con cualquier problema de pago</p>
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
    </script>
</body>
</html>