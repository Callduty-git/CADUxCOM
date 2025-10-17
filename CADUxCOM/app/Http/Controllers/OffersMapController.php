<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Empresa;
use App\Models\Categoria;

class OffersMapController extends Controller
{
    /**
     * Mostrar el mapa de ofertas
     */
    public function index()
    {
        // Obtener empresas con sus productos y ofertas
        $empresas = $this->getEmpresasWithOffers();
        
        // Obtener municipios del Huila para los filtros
        $municipiosHuila = $this->getMunicipiosHuila();
        
        // Obtener categorías para los filtros
        $categorias = $this->getCategorias();
        
        return view('geolocation.map', compact('empresas', 'municipiosHuila', 'categorias'));
    }
    
    public function testEmpresas()
    {
        try {
            $empresas = $this->getEmpresasWithOffers();
            return response()->json([
                'success' => true,
                'count' => count($empresas),
                'empresas' => $empresas
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'empresas' => []
            ]);
        }
    }
    
    /**
     * Buscar ofertas cercanas (API endpoint)
     */
    public function searchNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:1|max:100',
            'category' => 'nullable|integer'
        ]);
        
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 10; // km
        $category = $request->category;
        
        try {
            // Calcular empresas cercanas usando fórmula de Haversine
            $empresas = $this->getNearbyEmpresas($latitude, $longitude, $radius, $category);
            
            return response()->json([
                'success' => true,
                'data' => $empresas,
                'count' => count($empresas)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al buscar ofertas cercanas',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Obtener empresas con ofertas
     */
    private function getEmpresasWithOffers()
    {
        try {
            // Obtener empresas reales de la base de datos con coordenadas válidas
            $empresas = Empresa::withValidCoordinates()
                ->whereHas('productos', function($query) {
                    $query->where('productos.Activo', true);
                })
                ->with(['productos' => function($query) {
                    $query->where('productos.Activo', true)
                          ->orderBy('created_at', 'desc');
                }])
                ->get()
                ->map(function ($empresa) {
                    $mapInfo = $empresa->getMapInfo();
                    
                    // Obtener productos con información de descuentos
                    $productos = $empresa->productos->map(function ($producto) {
                        $hasDiscount = !is_null($producto->Precio_Descuento) && $producto->Precio_Descuento > 0;
                        $discountPercentage = 0;
                        
                        if ($hasDiscount && $producto->Precio > 0) {
                            $discountPercentage = round((($producto->Precio - $producto->Precio_Descuento) / $producto->Precio) * 100);
                        }
                        
                        // Determinar estado de vencimiento
                        $expiryStatus = 'fresh';
                        $expiryLabel = 'Fresco';
                        
                        if ($producto->Fecha_Vencimiento) {
                            $daysUntilExpiry = now()->diffInDays($producto->Fecha_Vencimiento, false);
                            
                            if ($daysUntilExpiry < 0) {
                                $expiryStatus = 'expired';
                                $expiryLabel = 'Vencido';
                            } elseif ($daysUntilExpiry <= 3) {
                                $expiryStatus = 'urgent';
                                $expiryLabel = 'Urgente';
                            } elseif ($daysUntilExpiry <= 7) {
                                $expiryStatus = 'near-expiry';
                                $expiryLabel = 'Por vencer';
                            }
                        }
                        
                        return [
                            'id' => $producto->Id_Producto,
                            'name' => $producto->Nombre,
                            'price' => (float) $producto->Precio,
                            'discounted_price' => $hasDiscount ? (float) $producto->Precio_Descuento : null,
                            'discount_percentage' => $discountPercentage,
                            'has_discount' => $hasDiscount,
                            'image' => $producto->Imagen ? asset('storage/' . $producto->Imagen) : asset('images/products/default.jpg'),
                            'category_id' => $producto->Id_Categoria,
                            'expiry_status' => $expiryStatus,
                            'expiry_label' => $expiryLabel,
                            'expiry_date' => $producto->Fecha_Vencimiento ? $producto->Fecha_Vencimiento->format('Y-m-d') : null
                        ];
                    });
                    
                    // Contar productos con descuento
                    $discountedProductsCount = $productos->where('has_discount', true)->count();
                    
                    return [
                        'id' => $empresa->Id_Empresa,
                        'name' => $empresa->Nombre,
                        'address' => $empresa->Direccion . ', ' . $empresa->Municipio . ', ' . $empresa->Departamento,
                        'coordinates' => [
                            'lat' => (float) $empresa->latitude,
                            'lng' => (float) $empresa->longitude
                        ],
                        'products_count' => $productos->count(),
                        'discounted_products_count' => $discountedProductsCount,
                        'products' => $productos->take(10)->values()->toArray(), // Limitar a 10 productos para el mapa
                        'phone' => $empresa->Telefono,
                        'email' => $empresa->Email,
                        'description' => $empresa->Descripcion,
                        'municipality' => $empresa->Municipio,
                        'department' => $empresa->Departamento
                    ];
                });
            
            \Log::info('Empresas cargadas para el mapa:', ['count' => $empresas->count()]);
            
            return $empresas->toArray();
        } catch (\Exception $e) {
            \Log::error('Error obteniendo empresas con ofertas: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Obtener municipios del Huila
     */
    private function getMunicipiosHuila()
    {
        return [
            'Acevedo',
            'Agrado',
            'Aipe',
            'Algeciras',
            'Altamira',
            'Baraya',
            'Campoalegre',
            'Colombia',
            'Elías',
            'Garzón',
            'Gigante',
            'Guadalupe',
            'Hobo',
            'Íquira',
            'Isnos',
            'La Argentina',
            'La Plata',
            'Nátaga',
            'Neiva',
            'Oporapa',
            'Paicol',
            'Palermo',
            'Palestina',
            'Pital',
            'Pitalito',
            'Rivera',
            'Saladoblanco',
            'San Agustín',
            'Santa María',
            'Suaza',
            'Tarqui',
            'Tello',
            'Teruel',
            'Tesalia',
            'Timaná',
            'Villavieja',
            'Yaguará'
        ];
    }
    
    /**
     * Obtener categorías
     */
    private function getCategorias()
    {
        try {
            return Categoria::orderBy('Nombre')->get();
        } catch (\Exception $e) {
            \Log::error('Error obteniendo categorías: ' . $e->getMessage());
            return collect();
        }
    }
    
    /**
     * Obtener empresas cercanas usando fórmula de Haversine
     */
    private function getNearbyEmpresas($latitude, $longitude, $radius, $category = null)
    {
        try {
            // Usar el scope nearTo del modelo Empresa para obtener empresas cercanas
            $query = Empresa::nearTo($latitude, $longitude, $radius)
                ->withValidCoordinates()
                ->whereHas('productos', function($query) {
                    $query->where('productos.Activo', true);
                })
                ->with(['productos' => function($query) {
                    $query->where('productos.Activo', true)
                          ->orderBy('created_at', 'desc');
                }]);
            
            // Filtrar por categoría si se especifica
            if ($category) {
                $query->whereHas('productos', function($q) use ($category) {
                    $q->where('Id_Categoria', $category);
                });
            }
            
            $empresas = $query->get()->map(function ($empresa) {
                $mapInfo = $empresa->getMapInfo();
                
                // Obtener productos con información de descuentos
                $productos = $empresa->productos->map(function ($producto) {
                    $hasDiscount = !is_null($producto->Precio_Descuento) && $producto->Precio_Descuento > 0;
                    $discountPercentage = 0;
                    
                    if ($hasDiscount && $producto->Precio > 0) {
                        $discountPercentage = round((($producto->Precio - $producto->Precio_Descuento) / $producto->Precio) * 100);
                    }
                    
                    // Determinar estado de vencimiento
                    $expiryStatus = 'fresh';
                    $expiryLabel = 'Fresco';
                    
                    if ($producto->Fecha_Vencimiento) {
                        $daysUntilExpiry = now()->diffInDays($producto->Fecha_Vencimiento, false);
                        
                        if ($daysUntilExpiry < 0) {
                            $expiryStatus = 'expired';
                            $expiryLabel = 'Vencido';
                        } elseif ($daysUntilExpiry <= 3) {
                            $expiryStatus = 'urgent';
                            $expiryLabel = 'Urgente';
                        } elseif ($daysUntilExpiry <= 7) {
                            $expiryStatus = 'near-expiry';
                            $expiryLabel = 'Por vencer';
                        }
                    }
                    
                    return [
                        'id' => $producto->Id_Producto,
                        'name' => $producto->Nombre,
                        'price' => (float) $producto->Precio,
                        'discounted_price' => $hasDiscount ? (float) $producto->Precio_Descuento : null,
                        'discount_percentage' => $discountPercentage,
                        'has_discount' => $hasDiscount,
                        'image' => $producto->Imagen ? asset('storage/' . $producto->Imagen) : asset('images/products/default.jpg'),
                        'category_id' => $producto->Id_Categoria,
                        'expiry_status' => $expiryStatus,
                        'expiry_label' => $expiryLabel,
                        'expiry_date' => $producto->Fecha_Vencimiento ? $producto->Fecha_Vencimiento->format('Y-m-d') : null
                    ];
                });
                
                // Contar productos con descuento
                $discountedProductsCount = $productos->where('has_discount', true)->count();
                
                return [
                    'id' => $empresa->Id_Empresa,
                    'name' => $empresa->Nombre,
                    'address' => $empresa->Direccion . ', ' . $empresa->Municipio . ', ' . $empresa->Departamento,
                    'coordinates' => [
                        'lat' => (float) $empresa->latitude,
                        'lng' => (float) $empresa->longitude
                    ],
                    'products_count' => $productos->count(),
                    'discounted_products_count' => $discountedProductsCount,
                    'products' => $productos->take(10)->values()->toArray(),
                    'phone' => $empresa->Telefono,
                    'email' => $empresa->Email,
                    'description' => $empresa->Descripcion,
                    'municipality' => $empresa->Municipio,
                    'department' => $empresa->Departamento,
                    'distance' => round($empresa->distance, 1) // La distancia viene del scope nearTo
                ];
            });
            
            // Ordenar por distancia
            return $empresas->sortBy('distance')->values()->toArray();
            
        } catch (\Exception $e) {
            \Log::error('Error obteniendo empresas cercanas: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Calcular distancia entre dos puntos usando fórmula de Haversine
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Radio de la Tierra en kilómetros
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
}

