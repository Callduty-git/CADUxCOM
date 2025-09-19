<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EducationController extends Controller
{
    /**
     * Mostrar página principal de educación
     */
    public function index()
    {
        try {
            $articles = $this->getEducationalArticles();
            $tips = $this->getFoodWasteTips();
            $statistics = $this->getFoodWasteStatistics();
            $recipes = $this->getRecipes();
            $processSteps = $this->getProcessSteps();
            $benefits = $this->getBenefits();

            return view('education.index', compact('articles', 'tips', 'statistics', 'recipes', 'processSteps', 'benefits'));
        } catch (\Exception $e) {
            \Log::error('Error en EducationController: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return view('education.index', [
                'articles' => [],
                'tips' => [],
                'statistics' => [],
                'recipes' => [],
                'processSteps' => [],
                'benefits' => []
            ]);
        }
    }

    /**
     * Mostrar calculadora de impacto
     */
    public function impactCalculator()
    {
        return view('education.impact-calculator');
    }

    /**
     * Calcular impacto del usuario
     */
    public function calculateImpact(Request $request)
    {
        $request->validate([
            'food_waste_per_week' => 'required|numeric|min:0',
            'household_size' => 'required|integer|min:1|max:20',
            'shopping_frequency' => 'required|string|in:daily,weekly,biweekly,monthly',
        ]);

        $foodWastePerWeek = $request->food_waste_per_week;
        $householdSize = $request->household_size;
        $shoppingFrequency = $request->shopping_frequency;

        // Cálculos de impacto
        $weeklyWaste = $foodWastePerWeek;
        $monthlyWaste = $weeklyWaste * 4.33;
        $yearlyWaste = $weeklyWaste * 52;

        // Costo económico (precio promedio por kg de comida en Colombia: $8,000 COP)
        $costPerKg = 8000;
        $weeklyCost = $weeklyWaste * $costPerKg;
        $monthlyCost = $monthlyWaste * $costPerKg;
        $yearlyCost = $yearlyWaste * $costPerKg;

        // Impacto ambiental (kg CO2 por kg de comida desperdiciada: 2.5 kg CO2)
        $co2PerKg = 2.5;
        $weeklyCO2 = $weeklyWaste * $co2PerKg;
        $monthlyCO2 = $monthlyWaste * $co2PerKg;
        $yearlyCO2 = $yearlyWaste * $co2PerKg;

        // Agua desperdiciada (litros por kg de comida: 1,000 litros)
        $waterPerKg = 1000;
        $weeklyWater = $weeklyWaste * $waterPerKg;
        $monthlyWater = $monthlyWaste * $waterPerKg;
        $yearlyWater = $yearlyWaste * $waterPerKg;

        // Recomendaciones
        $recommendations = $this->generateRecommendations($foodWastePerWeek, $householdSize, $shoppingFrequency);

        return response()->json([
            'success' => true,
            'data' => [
                'waste' => [
                    'weekly' => $weeklyWaste,
                    'monthly' => $monthlyWaste,
                    'yearly' => $yearlyWaste,
                ],
                'cost' => [
                    'weekly' => $weeklyCost,
                    'monthly' => $monthlyCost,
                    'yearly' => $yearlyCost,
                ],
                'environmental' => [
                    'co2' => [
                        'weekly' => $weeklyCO2,
                        'monthly' => $monthlyCO2,
                        'yearly' => $yearlyCO2,
                    ],
                    'water' => [
                        'weekly' => $weeklyWater,
                        'monthly' => $monthlyWater,
                        'yearly' => $yearlyWater,
                    ],
                ],
                'recommendations' => $recommendations,
            ],
        ]);
    }

    /**
     * Obtener artículos educativos
     */
    private function getEducationalArticles(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'El Impacto del Desperdicio de Alimentos en Colombia',
                'slug' => 'impacto-desperdicio-alimentos-colombia',
                'excerpt' => 'Descubre las cifras alarmantes del desperdicio de alimentos en nuestro país y cómo CADUxCOM está ayudando a combatirlo.',
                'category' => 'impacto',
                'image' => asset('images/education/food-waste-impact.jpg'),
                'read_time' => '5 min',
                'published_at' => '2024-01-15',
            ],
            [
                'id' => 2,
                'title' => 'Cómo Conservar Frutas y Verduras por Más Tiempo',
                'slug' => 'conservar-frutas-verduras',
                'excerpt' => 'Aprende técnicas simples y efectivas para extender la vida útil de tus frutas y verduras.',
                'category' => 'conservacion',
                'image' => asset('images/education/fruit-vegetable-storage.jpg'),
                'read_time' => '7 min',
                'published_at' => '2024-01-10',
            ],
            [
                'id' => 3,
                'title' => 'Recetas Creativas con Productos Próximos a Caducar',
                'slug' => 'recetas-productos-caducar',
                'excerpt' => 'Transforma productos próximos a caducar en deliciosas comidas con estas recetas innovadoras.',
                'category' => 'recetas',
                'image' => asset('images/education/creative-recipes.jpg'),
                'read_time' => '10 min',
                'published_at' => '2024-01-05',
            ],
        ];
    }

    /**
     * Obtener consejos sobre desperdicio de alimentos
     */
    private function getFoodWasteTips(): array
    {
        return [
            [
                'category' => 'Planificación',
                'icon' => 'fas fa-clipboard-list',
                'color' => '#90D575',
                'tips' => [
                    'Planifica tus comidas semanalmente',
                    'Haz una lista de compras antes de ir al supermercado',
                    'Revisa tu despensa antes de comprar',
                    'Compra solo lo que necesitas'
                ]
            ],
            [
                'category' => 'Almacenamiento',
                'icon' => 'fas fa-boxes',
                'color' => '#AA5FC7',
                'tips' => [
                    'Almacena los productos por fecha de vencimiento',
                    'Usa el método FIFO (First In, First Out)',
                    'Mantén tu refrigerador organizado',
                    'Congela alimentos que no usarás pronto'
                ]
            ],
            [
                'category' => 'Consumo Inteligente',
                'icon' => 'fas fa-lightbulb',
                'color' => '#49874E',
                'tips' => [
                    'Usa CADUxCOM para encontrar ofertas cercanas',
                    'Compra productos próximos a caducar con descuento',
                    'Aprende a leer etiquetas de vencimiento',
                    'Reutiliza sobras en nuevas recetas'
                ]
            ],
            [
                'category' => 'Ahorro',
                'icon' => 'fas fa-piggy-bank',
                'color' => '#f59e0b',
                'tips' => [
                    'Ahorra hasta 70% en productos de calidad',
                    'Reduce tu presupuesto de alimentación',
                    'Descubre nuevos productos a precios accesibles',
                    'Contribuye a la economía circular'
                ]
            ]
        ];
    }

    /**
     * Obtener estadísticas de desperdicio de alimentos
     */
    private function getFoodWasteStatistics(): array
    {
        return [
            [
                'title' => 'Desperdicio Mundial',
                'value' => '1.3 mil millones',
                'unit' => 'toneladas/año',
                'description' => 'de alimentos se desperdician anualmente en el mundo',
                'icon' => '🌍',
            ],
            [
                'title' => 'Desperdicio en Colombia',
                'value' => '9.76 millones',
                'unit' => 'toneladas/año',
                'description' => 'de alimentos se desperdician en nuestro país',
                'icon' => '🇨🇴',
            ],
            [
                'title' => 'Pérdida Económica',
                'value' => '$78 mil millones',
                'unit' => 'COP/año',
                'description' => 'es el costo del desperdicio de alimentos en Colombia',
                'icon' => '💸',
            ],
            [
                'title' => 'Impacto Ambiental',
                'value' => '8%',
                'unit' => 'de emisiones',
                'description' => 'de gases de efecto invernadero provienen del desperdicio de alimentos',
                'icon' => '🌱',
            ],
        ];
    }

    /**
     * Obtener recetas
     */
    private function getRecipes(): array
    {
        return [
            [
                'id' => 1,
                'title' => 'Sopa de Verduras con Productos Próximos a Caducar',
                'description' => 'Una sopa nutritiva que aprovecha todas las verduras que están a punto de caducar.',
                'ingredients' => ['Zanahorias', 'Apio', 'Cebolla', 'Tomates', 'Papa'],
                'prep_time' => '15 min',
                'cook_time' => '30 min',
                'difficulty' => 'Fácil',
                'servings' => 4,
                'image' => asset('images/education/vegetable-soup.jpg'),
                'category' => 'sopas',
            ],
            [
                'id' => 2,
                'title' => 'Pan de Plátano Maduro',
                'description' => 'Aprovecha esos plátanos muy maduros para hacer un delicioso pan casero.',
                'ingredients' => ['Plátanos maduros', 'Harina', 'Huevos', 'Azúcar', 'Mantequilla'],
                'prep_time' => '10 min',
                'cook_time' => '45 min',
                'difficulty' => 'Fácil',
                'servings' => 8,
                'image' => asset('images/education/banana-bread.jpg'),
                'category' => 'postres',
            ],
        ];
    }

    /**
     * Generar recomendaciones personalizadas
     */
    private function generateRecommendations(float $waste, int $householdSize, string $frequency): array
    {
        $recommendations = [];

        if ($waste > 2) {
            $recommendations[] = [
                'type' => 'warning',
                'title' => 'Alto desperdicio detectado',
                'message' => 'Tu desperdicio semanal es alto. Considera planificar mejor tus compras.',
                'action' => 'Ver consejos de planificación',
            ];
        }

        $recommendations[] = [
            'type' => 'success',
            'title' => 'Usa CADUxCOM',
            'message' => 'Encuentra productos con descuentos por proximidad a caducar.',
            'action' => 'Explorar ofertas',
        ];

        return $recommendations;
    }

    /**
     * Obtener pasos del proceso CADUxCOM
     */
    private function getProcessSteps(): array
    {
        return [
            [
                'step' => '1',
                'title' => 'Registro de Empresas',
                'description' => 'Los supermercados y restaurantes se registran en CADUxCOM y suben sus productos próximos a caducar.',
                'icon' => 'fas fa-store',
                'details' => [
                    'Registro gratuito y fácil',
                    'Subida de productos con fechas de vencimiento',
                    'Configuración de descuentos automáticos',
                    'Verificación de ubicación y datos'
                ]
            ],
            [
                'step' => '2',
                'title' => 'Descubrimiento de Ofertas',
                'description' => 'Los usuarios descubren ofertas cercanas usando nuestro mapa interactivo y filtros inteligentes.',
                'icon' => 'fas fa-map-marked-alt',
                'details' => [
                    'Mapa interactivo con geolocalización',
                    'Filtros por categoría y distancia',
                    'Búsqueda por productos específicos',
                    'Notificaciones de ofertas cercanas'
                ]
            ],
            [
                'step' => '3',
                'title' => 'Compra Consciente',
                'description' => 'Los usuarios compran productos con descuento, ahorrando dinero y reduciendo desperdicio.',
                'icon' => 'fas fa-shopping-cart',
                'details' => [
                    'Precios reducidos hasta 70%',
                    'Productos de calidad garantizada',
                    'Compra directa en el establecimiento',
                    'Contribución al medio ambiente'
                ]
            ]
        ];
    }

    /**
     * Obtener beneficios de CADUxCOM
     */
    private function getBenefits(): array
    {
        return [
            [
                'title' => 'Para Consumidores',
                'icon' => 'fas fa-user',
                'color' => '#90D575',
                'benefits' => [
                    'Ahorra hasta 70% en productos de calidad',
                    'Descubre ofertas cercanas a tu ubicación',
                    'Contribuye a reducir el desperdicio de alimentos',
                    'Accede a productos frescos a precios accesibles'
                ]
            ],
            [
                'title' => 'Para Empresas',
                'icon' => 'fas fa-building',
                'color' => '#AA5FC7',
                'benefits' => [
                    'Reduce pérdidas por productos próximos a caducar',
                    'Aumenta el flujo de clientes en tu establecimiento',
                    'Mejora tu imagen de responsabilidad social',
                    'Genera ingresos adicionales'
                ]
            ],
            [
                'title' => 'Para el Medio Ambiente',
                'icon' => 'fas fa-globe',
                'color' => '#49874E',
                'benefits' => [
                    'Reduce el desperdicio de alimentos',
                    'Disminuye las emisiones de gases de efecto invernadero',
                    'Promueve el consumo responsable',
                    'Contribuye a la economía circular'
                ]
            ]
        ];
    }
}