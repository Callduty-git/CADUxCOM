<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educación - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header-pages.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/education.css') }}?v={{ time() }}&force={{ rand(1000, 9999) }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Font Awesome para iconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Meta tags para SEO -->
    <meta name="description" content="Aprende sobre el desperdicio de alimentos y cómo CADUxCOM ayuda a reducirlo. Consejos, estadísticas y guías para un consumo más consciente.">
    <meta name="keywords" content="educación, desperdicio de alimentos, consumo consciente, sostenibilidad, CADUxCOM">
    <meta name="robots" content="index, follow">
    
    <!-- Estilos específicos para la página de educación -->
    <style>
        /* Espaciado superior específico para la página de educación */
        .education-container {
            margin-top: 0 !important;
            padding-top: 0 !important;
            padding-bottom: 40px;
            width: 100%;
            max-width: none;
            margin-left: 0;
            margin-right: 0;
            background: #fff;
            border-radius: 0;
            box-shadow: none;
        }
        
        /* Forzar separación del header */
        .page-container {
            padding-top: 100px !important;
            margin-top: 0 !important;
        }
        
        .hero-section {
            margin-top: 20px !important;
            padding-top: 6rem !important;
            padding-bottom: 4rem !important;
            z-index: 1 !important;
        }
        .container {
            padding: 0 32px;
        }
        @media (max-width: 1024px) {
            .education-container {
                width: 100vw;
                padding-bottom: 24px;
            }
            .container {
                padding: 0 12px;
            }
        }
        @media (max-width: 768px) {
            .education-container {
                margin-top: 100px;
                padding-top: 5px;
                padding-bottom: 12px;
            }
            .container {
                padding: 0 4vw;
            }
            .hero-title {
                font-size: 2rem;
            }
            .section-header h2 {
                font-size: 1.3rem;
            }
            .statistics-grid, .benefits-grid, .tips-grid, .articles-grid {
                grid-template-columns: 1fr !important;
                gap: 18px !important;
            }
        }
        .section-header {
            margin-bottom: 32px;
            text-align: center;
        }
        .section-header h2 {
            margin-bottom: 8px;
        }
        .statistics-grid, .benefits-grid, .tips-grid, .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 32px;
            width: 100%;
        }
        .stat-card, .benefit-card, .tip-card, .article-card {
            margin-bottom: 0;
            padding: 24px 18px;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(73,135,78,0.08);
            background: #fff;
            word-break: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        .stat-value, .stat-title, .stat-description, .benefit-list li, .tip-list li, .article-title, .article-excerpt {
            word-break: break-word;
            overflow-wrap: break-word;
        }
        .calculator-card {
            padding: 32px 18px;
            border-radius: 18px;
            box-shadow: 0 2px 16px rgba(73,135,78,0.08);
            background: #fff;
            margin-bottom: 32px;
            word-break: break-word;
            overflow-wrap: break-word;
            max-width: 100%;
        }
        .calculator-form .form-row {
            display: flex;
            gap: 24px;
            flex-wrap: wrap;
        }
        .calculator-form .form-group {
            flex: 1 1 220px;
            min-width: 220px;
            margin-bottom: 18px;
            position: relative;
        }
        .calculator-form .form-group input {
            padding-right: 4.5rem !important;
        }
        .calculator-form .input-unit {
            position: absolute !important;
            right: 1.2rem !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            color: #AA5FC7 !important;
            font-size: 0.9rem !important;
            font-weight: 600 !important;
            pointer-events: none !important;
            background: transparent !important;
            white-space: nowrap !important;
            z-index: 10 !important;
            line-height: 1 !important;
        }
        @media (max-width: 600px) {
            .calculator-form .form-row {
                flex-direction: column;
                gap: 8px;
            }
            .calculator-card {
                padding: 18px 4px;
            }
            .calculator-form .form-group input {
                padding-right: 3.5rem !important;
            }
            .calculator-form .input-unit {
                right: 1rem !important;
                font-size: 0.85rem !important;
            }
        }
        @media (max-width: 480px) {
            .calculator-form .form-group input {
                padding-right: 2.8rem !important;
            }
            .calculator-form .input-unit {
                right: 0.8rem !important;
                font-size: 0.75rem !important;
            }
        }
        
        /* Estilos responsivos para los campos con unidades inline */
        @media (max-width: 768px) {
            .form-group div[style*="position: relative"] input {
                padding-right: 4rem !important;
            }
            .form-group div[style*="position: relative"] span {
                right: 0.8rem !important;
                font-size: 0.85rem !important;
            }
        }
        
        @media (max-width: 480px) {
            .form-group div[style*="position: relative"] input {
                padding-right: 3rem !important;
            }
            .form-group div[style*="position: relative"] span {
                right: 0.6rem !important;
                font-size: 0.75rem !important;
            }
        }
        .cta-section {
            margin-top: 0;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        .cta-content {
            margin-bottom: 24px; /* Espaciado interno antes del cierre, no sobre el footer */
            background: linear-gradient(135deg, #89CF6D 0%, #49874E 100%);
            border-radius: 18px;
            padding: 32px 24px;
            color: #fff;
            box-shadow: 0 2px 16px rgba(73,135,78,0.08);
        }
        .cta-text h2, .cta-text p {
            color: #fff;
            text-shadow: 0 2px 8px rgba(73,135,78,0.18);
        }
        .cta-text p {
            font-size: 1.15rem;
            margin-bottom: 18px;
        }
        .cta-actions .btn {
            box-shadow: 0 2px 8px rgba(73,135,78,0.10);
        }
        @media (max-width: 700px) {
            .cta-content {
                flex-direction: column;
                gap: 18px;
                padding: 18px 8px;
            }
            .cta-text {
                text-align: center;
            }
            .cta-actions {
                justify-content: center;
            }
        }
        /* Eliminar espacio extra antes del footer */
        .education-container {
            margin-bottom: 0 !important;
            padding-bottom: 0 !important;
        }
    </style>
</head>
<body>
    <x-header-pages />
    
    <div class="education-container">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        <span class="title-highlight">Educación</span> sobre 
                        <span class="title-accent">Desperdicio de Alimentos</span>
                    </h1>
                    <p class="hero-subtitle">
                        Aprende a reducir el desperdicio de alimentos, ahorra dinero y contribuye a un mundo más sostenible con CADUxCOM
                    </p>
                    <div class="hero-actions">
                        <a href="#como-funciona" class="btn btn-primary">
                            <i class="fas fa-play-circle"></i>
                            Cómo Funciona CADUxCOM
                        </a>
                        <a href="#consejos" class="btn btn-secondary">
                            <i class="fas fa-lightbulb"></i>
                            Ver Consejos
                        </a>
                    </div>
                </div>
                <div class="hero-visual">
                    <div class="hero-icon">
                        <i class="fas fa-leaf"></i>
                    </div>
                    <div class="floating-elements">
                        <div class="floating-icon icon-1"><i class="fas fa-apple-alt"></i></div>
                        <div class="floating-icon icon-2"><i class="fas fa-carrot"></i></div>
                        <div class="floating-icon icon-3"><i class="fas fa-bread-slice"></i></div>
                        <div class="floating-icon icon-4"><i class="fas fa-cheese"></i></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Estadísticas Impactantes -->
        <section class="statistics-section" id="estadisticas">
            <div class="container">
                <div class="section-header">
                    <h2>El Problema del Desperdicio de Alimentos</h2>
                    <p>Datos que nos motivan a actuar y cambiar nuestros hábitos</p>
                </div>
                
                <div class="statistics-grid">
                    @foreach($statistics as $stat)
                        <div class="stat-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="stat-icon">{{ $stat['icon'] }}</div>
                            <div class="stat-content">
                                <div class="stat-value">{{ $stat['value'] }}</div>
                                <div class="stat-unit">{{ $stat['unit'] }}</div>
                                <div class="stat-title">{{ $stat['title'] }}</div>
                                <div class="stat-description">{{ $stat['description'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Cómo Funciona CADUxCOM -->
        <section class="process-section" id="como-funciona">
            <div class="container">
                <div class="section-header">
                    <h2>¿Cómo Funciona CADUxCOM?</h2>
                    <p>Un proceso simple que conecta empresas y consumidores para reducir el desperdicio</p>
                </div>
                
                <div class="process-timeline">
                    @foreach($processSteps as $step)
                        <div class="process-step" data-aos="fade-right" data-aos-delay="{{ $loop->index * 200 }}">
                            <div class="step-number">{{ $step['step'] }}</div>
                            <div class="step-content">
                                <div class="step-icon">
                                    <i class="{{ $step['icon'] }}"></i>
                                </div>
                                <div class="step-info">
                                    <h3>{{ $step['title'] }}</h3>
                                    <p>{{ $step['description'] }}</p>
                                    <ul class="step-details">
                                        @foreach($step['details'] as $detail)
                                            <li><i class="fas fa-check"></i> {{ $detail }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Beneficios -->
        <section class="benefits-section" id="beneficios">
            <div class="container">
                <div class="section-header">
                    <h2>Beneficios para Todos</h2>
                    <p>CADUxCOM crea valor para consumidores, empresas y el medio ambiente</p>
                </div>
                
                <div class="benefits-grid">
                    @foreach($benefits as $benefit)
                        <div class="benefit-card" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 150 }}">
                            <div class="benefit-header">
                                <div class="benefit-icon" style="background-color: {{ $benefit['color'] }}">
                                    <i class="{{ $benefit['icon'] }}"></i>
                                </div>
                                <h3>{{ $benefit['title'] }}</h3>
                            </div>
                            <ul class="benefit-list">
                                @foreach($benefit['benefits'] as $item)
                                    <li><i class="fas fa-check-circle"></i> {{ $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Consejos Prácticos -->
        <section class="tips-section" id="consejos">
            <div class="container">
                <div class="section-header">
                    <h2>Consejos para Consumo Consciente</h2>
                    <p>Acciones simples que marcan la diferencia en tu hogar y en el planeta</p>
                </div>
                
                <div class="tips-grid">
                    @foreach($tips as $tip)
                        <div class="tip-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="tip-header">
                                <div class="tip-icon" style="background-color: {{ $tip['color'] ?? '#90D575' }}">
                                    <i class="{{ $tip['icon'] ?? 'fas fa-lightbulb' }}"></i>
                                </div>
                                <h3>{{ $tip['category'] ?? 'Consejo' }}</h3>
                            </div>
                            <ul class="tip-list">
                                @foreach(($tip['tips'] ?? []) as $tipItem)
                                    <li><i class="fas fa-arrow-right"></i> {{ $tipItem }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Calculadora de Impacto -->
        <section class="calculator-section" id="calculadora">
            <div class="container">
                <div class="section-header">
                    <h2>Calculadora de Impacto</h2>
                    <p>Descubre cuánto desperdicias y cómo puedes mejorar</p>
                </div>
                
                <div class="calculator-card">
                    <form id="impact-calculator-form" class="calculator-form">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="food_waste">
                                    <i class="fas fa-weight"></i>
                                    ¿Cuántos kg de comida desperdicias por semana?
                                </label>
                                <div style="position: relative; display: inline-block; width: 100%;">
                                    <input type="number" id="food_waste" name="food_waste_per_week" step="0.1" min="0" max="50" required style="width: 100%; padding-right: 5rem; padding-left: 1rem; padding-top: 0.8rem; padding-bottom: 0.8rem; border: 2px solid #89CF6D; border-radius: 10px; font-size: 1rem; background: #F8FFF6; color: #49874E; box-sizing: border-box;">
                                    <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #AA5FC7; font-size: 0.9rem; font-weight: 600; pointer-events: none; background: transparent; white-space: nowrap; z-index: 10;">kg/semana</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="household_size">
                                    <i class="fas fa-users"></i>
                                    ¿Cuántas personas viven en tu hogar?
                                </label>
                                <div style="position: relative; display: inline-block; width: 100%;">
                                    <input type="number" id="household_size" name="household_size" min="1" max="20" required style="width: 100%; padding-right: 5rem; padding-left: 1rem; padding-top: 0.8rem; padding-bottom: 0.8rem; border: 2px solid #89CF6D; border-radius: 10px; font-size: 1rem; background: #F8FFF6; color: #49874E; box-sizing: border-box;">
                                    <span style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); color: #AA5FC7; font-size: 0.9rem; font-weight: 600; pointer-events: none; background: transparent; white-space: nowrap; z-index: 10;">personas</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="shopping_frequency">
                                <i class="fas fa-shopping-cart"></i>
                                ¿Con qué frecuencia haces compras?
                            </label>
                            <select id="shopping_frequency" name="shopping_frequency" required>
                                <option value="">Selecciona una opción</option>
                                <option value="daily">Diariamente</option>
                                <option value="weekly">Semanalmente</option>
                                <option value="biweekly">Quincenalmente</option>
                                <option value="monthly">Mensualmente</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary btn-large">
                            <i class="fas fa-calculator"></i>
                            Calcular mi Impacto
                        </button>
                    </form>

                    <div id="calculator-results" class="calculator-results" style="display: none;">
                        <h3>Tu Impacto Anual</h3>
                        <div class="results-grid">
                            <div class="result-card waste">
                                <div class="result-icon"><i class="fas fa-trash"></i></div>
                                <div class="result-content">
                                    <div class="result-value" id="waste-result">0 kg</div>
                                    <div class="result-label">Desperdicio</div>
                                </div>
                            </div>
                            <div class="result-card cost">
                                <div class="result-icon"><i class="fas fa-dollar-sign"></i></div>
                                <div class="result-content">
                                    <div class="result-value" id="cost-result">$0</div>
                                    <div class="result-label">Costo</div>
                                </div>
                            </div>
                            <div class="result-card co2">
                                <div class="result-icon"><i class="fas fa-globe"></i></div>
                                <div class="result-content">
                                    <div class="result-value" id="co2-result">0 kg</div>
                                    <div class="result-label">CO2</div>
                                </div>
                            </div>
                            <div class="result-card water">
                                <div class="result-icon"><i class="fas fa-tint"></i></div>
                                <div class="result-content">
                                    <div class="result-value" id="water-result">0 L</div>
                                    <div class="result-label">Agua</div>
                                </div>
                            </div>
                        </div>

                        <div id="recommendations" class="recommendations">
                            <h4>Recomendaciones Personalizadas</h4>
                            <div id="recommendations-list" class="recommendations-list"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Artículos Educativos -->
        <section class="articles-section" id="articulos">
            <div class="container">
                <div class="section-header">
                    <h2>Artículos Educativos</h2>
                    <p>Aprende más sobre el desperdicio de alimentos y cómo combatirlo</p>
                </div>
                
                <div class="articles-grid">
                    @foreach($articles as $article)
                        <div class="article-card" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="article-image">
                                <div class="article-placeholder">
                                    <i class="fas fa-newspaper"></i>
                                    <span>{{ ucfirst($article['category']) }}</span>
                                </div>
                            </div>
                            <div class="article-content">
                                <div class="article-meta">
                                    <span class="article-category">{{ ucfirst($article['category']) }}</span>
                                    <span class="article-time">{{ $article['read_time'] }}</span>
                                </div>
                                <h3 class="article-title">{{ $article['title'] }}</h3>
                                <p class="article-excerpt">{{ $article['excerpt'] }}</p>
                                <a href="#" class="article-link">
                                    Leer más <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <div class="cta-text">
                        <h2>¡Únete a la Lucha contra el Desperdicio!</h2>
                        <p>Usa CADUxCOM para encontrar productos con descuentos por proximidad a caducar y contribuye a un mundo más sostenible.</p>
                    </div>
                    <div class="cta-actions">
                        <a href="{{ route('productos.public.index') }}" class="btn btn-primary btn-large">
                            <i class="fas fa-search"></i>
                            Explorar Ofertas
                        </a>
                        <a href="{{ route('mapa') }}" class="btn btn-secondary btn-large">
                            <i class="fas fa-map-marked-alt"></i>
                            Ver Mapa de Ofertas
                        </a>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <x-footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('impact-calculator-form');
            const results = document.getElementById('calculator-results');

            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                const data = Object.fromEntries(formData);

                try {
                    const response = await fetch('/educacion/calcular-impacto', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (result.success) {
                        displayResults(result.data);
                        results.style.display = 'block';
                        results.scrollIntoView({ behavior: 'smooth' });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showModalAlert({
                        title: 'Error',
                        message: 'Error al calcular el impacto. Inténtalo de nuevo.',
                        confirmText: 'Cerrar',
                        color: '#AA5FC7',
                        accent: '#49874E'
                    });
                }
            });

            function displayResults(data) {
                // Actualizar valores
                document.getElementById('waste-result').textContent = Math.round(data.waste.yearly) + ' kg';
                document.getElementById('cost-result').textContent = '$' + Math.round(data.cost.yearly).toLocaleString();
                document.getElementById('co2-result').textContent = Math.round(data.environmental.co2.yearly) + ' kg';
                document.getElementById('water-result').textContent = Math.round(data.environmental.water.yearly).toLocaleString() + ' L';

                // Mostrar recomendaciones
                const recommendationsList = document.getElementById('recommendations-list');
                recommendationsList.innerHTML = '';

                data.recommendations.forEach(rec => {
                    const recElement = document.createElement('div');
                    recElement.className = `recommendation-item ${rec.type}`;
                    recElement.innerHTML = `
                        <div class="recommendation-content">
                            <h5>${rec.title}</h5>
                            <p>${rec.message}</p>
                            <button class="btn btn-sm btn-outline">${rec.action}</button>
                        </div>
                    `;
                    recommendationsList.appendChild(recElement);
                });
            }

            // Smooth scrolling para enlaces internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });
    </script>
    <script src="{{ asset('js/modal-alert.js') }}"></script>
</body>
</html>