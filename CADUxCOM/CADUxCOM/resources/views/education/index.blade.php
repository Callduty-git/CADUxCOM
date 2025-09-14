<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Educación sobre Desperdicio de Alimentos - CADUxCOM</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/education.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <x-header-pages />
    
    <div class="education-container">
        <!-- Hero Section -->
        <div class="hero-section">
            <div class="hero-content">
                <h1 class="hero-title">Educación sobre Desperdicio de Alimentos</h1>
                <p class="hero-subtitle">Aprende a reducir el desperdicio de alimentos y contribuye a un mundo más sostenible</p>
                <div class="hero-actions">
                    <a href="#calculator" class="btn btn-primary">Calcula tu Impacto</a>
                    <a href="#tips" class="btn btn-secondary">Ver Consejos</a>
                </div>
            </div>
            <div class="hero-image">
                <div class="hero-icon">🌱</div>
            </div>
        </div>

        <!-- Estadísticas Impactantes -->
        <div class="statistics-section" id="statistics">
            <div class="section-header">
                <h2>El Problema del Desperdicio de Alimentos</h2>
                <p>Datos que nos motivan a actuar</p>
            </div>
            
            <div class="statistics-grid">
                @foreach($statistics as $stat)
                    <div class="stat-card">
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

        <!-- Calculadora de Impacto -->
        <div class="calculator-section" id="calculator">
            <div class="section-header">
                <h2>Calculadora de Impacto</h2>
                <p>Descubre cuánto desperdicias y cómo puedes mejorar</p>
            </div>
            
            <div class="calculator-card">
                <form id="impact-calculator-form" class="calculator-form">
                    <div class="form-group">
                        <label for="food_waste">¿Cuántos kg de comida desperdicias por semana?</label>
                        <input type="number" id="food_waste" name="food_waste_per_week" step="0.1" min="0" max="50" required>
                        <span class="input-unit">kg/semana</span>
                    </div>

                    <div class="form-group">
                        <label for="household_size">¿Cuántas personas viven en tu hogar?</label>
                        <input type="number" id="household_size" name="household_size" min="1" max="20" required>
                        <span class="input-unit">personas</span>
                    </div>

                    <div class="form-group">
                        <label for="shopping_frequency">¿Con qué frecuencia haces compras?</label>
                        <select id="shopping_frequency" name="shopping_frequency" required>
                            <option value="">Selecciona una opción</option>
                            <option value="daily">Diariamente</option>
                            <option value="weekly">Semanalmente</option>
                            <option value="biweekly">Quincenalmente</option>
                            <option value="monthly">Mensualmente</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-large">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        Calcular mi Impacto
                    </button>
                </form>

                <div id="calculator-results" class="calculator-results" style="display: none;">
                    <h3>Tu Impacto Anual</h3>
                    <div class="results-grid">
                        <div class="result-card waste">
                            <div class="result-icon">🗑️</div>
                            <div class="result-content">
                                <div class="result-value" id="waste-result">0 kg</div>
                                <div class="result-label">Desperdicio</div>
                            </div>
                        </div>
                        <div class="result-card cost">
                            <div class="result-icon">💰</div>
                            <div class="result-content">
                                <div class="result-value" id="cost-result">$0</div>
                                <div class="result-label">Costo</div>
                            </div>
                        </div>
                        <div class="result-card co2">
                            <div class="result-icon">🌍</div>
                            <div class="result-content">
                                <div class="result-value" id="co2-result">0 kg</div>
                                <div class="result-label">CO2</div>
                            </div>
                        </div>
                        <div class="result-card water">
                            <div class="result-icon">💧</div>
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

        <!-- Consejos Prácticos -->
        <div class="tips-section" id="tips">
            <div class="section-header">
                <h2>Consejos para Reducir el Desperdicio</h2>
                <p>Acciones simples que marcan la diferencia</p>
            </div>
            
            <div class="tips-grid">
                @foreach($tips as $tip)
                    <div class="tip-card">
                        <div class="tip-icon">{{ $tip['icon'] }}</div>
                        <div class="tip-content">
                            <h3>{{ $tip['title'] }}</h3>
                            <p>{{ $tip['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Artículos Educativos -->
        <div class="articles-section">
            <div class="section-header">
                <h2>Artículos Educativos</h2>
                <p>Aprende más sobre el desperdicio de alimentos</p>
            </div>
            
            <div class="articles-grid">
                @foreach($articles as $article)
                    <div class="article-card">
                        <div class="article-image">
                            <div class="article-placeholder">{{ $article['category'] }}</div>
                        </div>
                        <div class="article-content">
                            <div class="article-meta">
                                <span class="article-category">{{ ucfirst($article['category']) }}</span>
                                <span class="article-time">{{ $article['read_time'] }}</span>
                            </div>
                            <h3 class="article-title">{{ $article['title'] }}</h3>
                            <p class="article-excerpt">{{ $article['excerpt'] }}</p>
                            <a href="#" class="article-link">Leer más →</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Recetas con Productos Próximos a Caducar -->
        <div class="recipes-section">
            <div class="section-header">
                <h2>Recetas Creativas</h2>
                <p>Transforma productos próximos a caducar en deliciosas comidas</p>
            </div>
            
            <div class="recipes-grid">
                @foreach($recipes as $recipe)
                    <div class="recipe-card">
                        <div class="recipe-image">
                            <div class="recipe-placeholder">{{ $recipe['category'] }}</div>
                        </div>
                        <div class="recipe-content">
                            <h3 class="recipe-title">{{ $recipe['title'] }}</h3>
                            <p class="recipe-description">{{ $recipe['description'] }}</p>
                            <div class="recipe-meta">
                                <span class="recipe-time">⏱️ {{ $recipe['prep_time'] }} + {{ $recipe['cook_time'] }}</span>
                                <span class="recipe-servings">👥 {{ $recipe['servings'] }} porciones</span>
                                <span class="recipe-difficulty">📊 {{ $recipe['difficulty'] }}</span>
                            </div>
                            <div class="recipe-ingredients">
                                <strong>Ingredientes:</strong>
                                <ul>
                                    @foreach($recipe['ingredients'] as $ingredient)
                                        <li>{{ $ingredient }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Call to Action -->
        <div class="cta-section">
            <div class="cta-content">
                <h2>¡Únete a la Lucha contra el Desperdicio!</h2>
                <p>Usa CADUxCOM para encontrar productos con descuentos por proximidad a caducar y contribuye a un mundo más sostenible.</p>
                <div class="cta-actions">
                    <a href="{{ route('productos.index') }}" class="btn btn-primary btn-large">Explorar Ofertas</a>
                    <a href="{{ route('mapa') }}" class="btn btn-secondary btn-large">Ver Mapa de Ofertas</a>
                </div>
            </div>
        </div>
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
                    alert('Error al calcular el impacto. Inténtalo de nuevo.');
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
        });
    </script>
</body>
</html>
