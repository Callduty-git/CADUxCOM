<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Controlador GeolocationController - Maneja funcionalidades de geolocalización
 */
class GeolocationController extends Controller
{
    /**
     * Mostrar mapa interactivo con ofertas cercanas
     */
    public function map()
    {
        $empresas = Empresa::withValidCoordinates()
            ->with(['productos' => function ($query) {
                $query->where('Cantidad', '>', 0);
            }])
            ->get()
            ->map(function ($empresa) {
                $mapInfo = $empresa->getMapInfo();
                $mapInfo['products'] = $empresa->productos->map(function ($producto) {
                    $discountInfo = $producto->getDiscountInfo();
                    return [
                        'id' => $producto->Id_Producto,
                        'name' => $producto->Nombre,
                        'price' => $producto->Precio,
                        'discounted_price' => $discountInfo['discounted_price'],
                        'has_discount' => $discountInfo['has_discount'],
                        'discount_percentage' => $discountInfo['discount_percentage'],
                        'expiry_status' => $discountInfo['expiry_status'],
                        'expiry_label' => $discountInfo['expiry_label'],
                        'days_until_expiry' => $discountInfo['days_until_expiry'],
                        'image' => $producto->Foto ? asset('storage/' . $producto->Foto) : asset('images/default-product.png'),
                    ];
                });
                return $mapInfo;
            });

        $categorias = Categoria::all();

        return view('geolocation.map', compact('empresas', 'categorias'));
    }

    /**
     * Buscar empresas por proximidad
     */
    public function searchNearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:100',
            'category' => 'nullable|integer|exists:categorias,Id_Categoria',
            'has_discount' => 'nullable|boolean',
        ]);

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;
        $radius = (float) ($request->radius ?? 10);

        $query = Empresa::nearTo($latitude, $longitude, $radius)
            ->with(['productos' => function ($query) use ($request) {
                $query->where('Cantidad', '>', 0);
                
                if ($request->category) {
                    $query->where('Id_Subcategoria', $request->category);
                }
            }]);

        $empresas = $query->get()->map(function ($empresa) {
            $mapInfo = $empresa->getMapInfo();
            $mapInfo['distance'] = round($empresa->distance, 2);
            
            $productos = $empresa->productos;
            if (request('has_discount')) {
                $productos = $productos->filter(function ($producto) {
                    return $producto->hasDiscount();
                });
            }

            $mapInfo['products'] = $productos->map(function ($producto) {
                $discountInfo = $producto->getDiscountInfo();
                return [
                    'id' => $producto->Id_Producto,
                    'name' => $producto->Nombre,
                    'price' => $producto->Precio,
                    'discounted_price' => $discountInfo['discounted_price'],
                    'has_discount' => $discountInfo['has_discount'],
                    'discount_percentage' => $discountInfo['discount_percentage'],
                    'expiry_status' => $discountInfo['expiry_status'],
                    'expiry_label' => $discountInfo['expiry_label'],
                    'days_until_expiry' => $discountInfo['days_until_expiry'],
                    'image' => $producto->Foto ? asset('storage/' . $producto->Foto) : asset('images/default-product.png'),
                ];
            });

            return $mapInfo;
        });

        return response()->json([
            'success' => true,
            'data' => $empresas,
            'search_params' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius,
                'total_results' => $empresas->count(),
            ],
        ]);
    }

    /**
     * Obtener ubicación del usuario
     */
    public function getUserLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $latitude = (float) $request->latitude;
        $longitude = (float) $request->longitude;

        session([
            'user_location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'updated_at' => now(),
            ]
        ]);

        return response()->json([
            'success' => true,
            'location' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
        ]);
    }

    /**
     * Obtener estadísticas de geolocalización
     */
    public function getStats()
    {
        $totalEmpresas = Empresa::count();
        $empresasConCoordenadas = Empresa::withValidCoordinates()->count();
        $empresasVerificadas = Empresa::verified()->count();

        $ciudades = Empresa::withValidCoordinates()
            ->selectRaw('city, COUNT(*) as count')
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total_empresas' => $totalEmpresas,
                'empresas_con_coordenadas' => $empresasConCoordenadas,
                'empresas_verificadas' => $empresasVerificadas,
                'coverage_percentage' => $totalEmpresas > 0 ? round(($empresasConCoordenadas / $totalEmpresas) * 100, 2) : 0,
                'top_ciudades' => $ciudades,
            ],
        ]);
    }
}