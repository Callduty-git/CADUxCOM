<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            // Simular datos de empresas para el mapa
            // En una implementación real, esto vendría de la base de datos
            $empresas = [
                [
                    'id' => 1,
                    'name' => 'Supermercado CADUxCOM Neiva',
                    'address' => 'Carrera 5 #15-20, Neiva, Huila',
                    'coordinates' => [
                        'lat' => 2.9271,
                        'lng' => -75.2819
                    ],
                    'products_count' => 15,
                    'discounted_products_count' => 8,
                    'products' => collect([
                        [
                            'id' => 1,
                            'name' => 'Arroz Diana 500g',
                            'price' => 2500,
                            'discounted_price' => 2000,
                            'discount_percentage' => 20,
                            'has_discount' => true,
                            'image' => asset('images/products/arroz-diana.jpg'),
                            'category_id' => 1,
                            'expiry_status' => 'fresh',
                            'expiry_label' => 'Fresco'
                        ],
                        [
                            'id' => 2,
                            'name' => 'Leche Alquería 1L',
                            'price' => 4500,
                            'discounted_price' => 3600,
                            'discount_percentage' => 20,
                            'has_discount' => true,
                            'image' => asset('images/products/leche-alqueria.jpg'),
                            'category_id' => 2,
                            'expiry_status' => 'urgent',
                            'expiry_label' => 'Urgente'
                        ]
                    ])
                ],
                [
                    'id' => 2,
                    'name' => 'Tienda CADUxCOM Pitalito',
                    'address' => 'Calle 10 #8-15, Pitalito, Huila',
                    'coordinates' => [
                        'lat' => 1.8536,
                        'lng' => -76.0508
                    ],
                    'products_count' => 12,
                    'discounted_products_count' => 5,
                    'products' => collect([
                        [
                            'id' => 3,
                            'name' => 'Pan Bimbo Integral',
                            'price' => 3200,
                            'discounted_price' => 2560,
                            'discount_percentage' => 20,
                            'has_discount' => true,
                            'image' => asset('images/products/pan-bimbo.jpg'),
                            'category_id' => 3,
                            'expiry_status' => 'near-expiry',
                            'expiry_label' => 'Por vencer'
                        ]
                    ])
                ],
                [
                    'id' => 3,
                    'name' => 'Mercado CADUxCOM Garzón',
                    'address' => 'Plaza Principal, Garzón, Huila',
                    'coordinates' => [
                        'lat' => 2.1961,
                        'lng' => -75.6277
                    ],
                    'products_count' => 8,
                    'discounted_products_count' => 3,
                    'products' => collect([
                        [
                            'id' => 4,
                            'name' => 'Aceite Gourmet 900ml',
                            'price' => 8500,
                            'discounted_price' => 6800,
                            'discount_percentage' => 20,
                            'has_discount' => true,
                            'image' => asset('images/products/aceite-gourmet.jpg'),
                            'category_id' => 4,
                            'expiry_status' => 'fresh',
                            'expiry_label' => 'Fresco'
                        ]
                    ])
                ]
            ];
            
            return $empresas;
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
            'Neiva',
            'Pitalito',
            'Garzón',
            'La Plata',
            'San Agustín',
            'Timaná',
            'Oporapa',
            'Palermo',
            'Rivera',
            'Campoalegre',
            'Algeciras',
            'Íquira',
            'Nátaga',
            'Paicol',
            'Tesalia',
            'Villavieja',
            'Yaguará',
            'Aipe',
            'Colombia',
            'Hobo',
            'Palestina',
            'Pital',
            'Saladoblanco',
            'Santa María',
            'Suaza',
            'Tarqui',
            'Tello',
            'Teruel',
            'Villavieja'
        ];
    }
    
    /**
     * Obtener categorías
     */
    private function getCategorias()
    {
        return collect([
            (object)['Id_Categoria' => 1, 'Nombre' => 'Granos y Cereales'],
            (object)['Id_Categoria' => 2, 'Nombre' => 'Lácteos'],
            (object)['Id_Categoria' => 3, 'Nombre' => 'Panadería'],
            (object)['Id_Categoria' => 4, 'Nombre' => 'Aceites y Condimentos'],
            (object)['Id_Categoria' => 5, 'Nombre' => 'Frutas y Verduras'],
            (object)['Id_Categoria' => 6, 'Nombre' => 'Carnes y Embutidos'],
            (object)['Id_Categoria' => 7, 'Nombre' => 'Bebidas'],
            (object)['Id_Categoria' => 8, 'Nombre' => 'Snacks y Dulces']
        ]);
    }
    
    /**
     * Obtener empresas cercanas usando fórmula de Haversine
     */
    private function getNearbyEmpresas($latitude, $longitude, $radius, $category = null)
    {
        // En una implementación real, esto haría una consulta SQL con la fórmula de Haversine
        // Por ahora, simulamos con datos estáticos
        
        $allEmpresas = $this->getEmpresasWithOffers();
        $nearbyEmpresas = [];
        
        foreach ($allEmpresas as $empresa) {
            $distance = $this->calculateDistance(
                $latitude, 
                $longitude, 
                $empresa['coordinates']['lat'], 
                $empresa['coordinates']['lng']
            );
            
            if ($distance <= $radius) {
                $empresa['distance'] = round($distance, 1);
                $nearbyEmpresas[] = $empresa;
            }
        }
        
        // Ordenar por distancia
        usort($nearbyEmpresas, function($a, $b) {
            return $a['distance'] <=> $b['distance'];
        });
        
        return $nearbyEmpresas;
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
