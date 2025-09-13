<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DiscountRule;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmpresaAdvancedDashboardController extends Controller
{
    /**
     * Mostrar dashboard avanzado de la empresa
     */
    public function index()
    {
        $empresa = Auth::guard('empresa')->user();
        
        // Métricas principales
        $metrics = $this->getMainMetrics($empresa);
        
        // Gráficos de ventas
        $salesData = $this->getSalesData($empresa);
        
        // Productos más vendidos
        $topProducts = $this->getTopProducts($empresa);
        
        // Análisis de descuentos
        $discountAnalysis = $this->getDiscountAnalysis($empresa);
        
        // Productos próximos a caducar
        $expiringProducts = $this->getExpiringProducts($empresa);
        
        // Estadísticas de geolocalización
        $locationStats = $this->getLocationStats($empresa);
        
        // Notificaciones recientes
        $recentNotifications = $this->getRecentNotifications($empresa);
        
        // Tendencias de ventas
        $salesTrends = $this->getSalesTrends($empresa);
        
        // Análisis de clientes
        $customerAnalysis = $this->getCustomerAnalysis($empresa);

        return view('empresa.advanced-dashboard', compact(
            'empresa',
            'metrics',
            'salesData',
            'topProducts',
            'discountAnalysis',
            'expiringProducts',
            'locationStats',
            'recentNotifications',
            'salesTrends',
            'customerAnalysis'
        ));
    }

    /**
     * Obtener métricas principales
     */
    private function getMainMetrics(Empresa $empresa): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();

        // Ventas del mes actual
        $currentMonthSales = OrderItem::whereHas('order', function ($query) use ($empresa) {
            $query->where('empresa_id', $empresa->Id_Empresa);
        })
        ->where('created_at', '>=', $thisMonth)
        ->sum(DB::raw('precio * cantidad'));

        // Ventas del mes anterior
        $lastMonthSales = OrderItem::whereHas('order', function ($query) use ($empresa) {
            $query->where('empresa_id', $empresa->Id_Empresa);
        })
        ->whereBetween('created_at', [$lastMonth, $thisMonth])
        ->sum(DB::raw('precio * cantidad'));

        // Crecimiento de ventas
        $salesGrowth = $lastMonthSales > 0 
            ? (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100 
            : 0;

        // Productos con descuento
        $discountedProducts = $empresa->productos()
            ->where('Cantidad', '>', 0)
            ->get()
            ->filter(function ($producto) {
                return $producto->hasDiscount();
            })->count();

        // Productos próximos a caducar
        $expiringProducts = $empresa->productos()
            ->where('Cantidad', '>', 0)
            ->whereNotNull('Fecha_Caducidad')
            ->where('Fecha_Caducidad', '<=', now()->addDays(7))
            ->count();

        // Órdenes del mes
        $monthlyOrders = Order::where('empresa_id', $empresa->Id_Empresa)
            ->where('created_at', '>=', $thisMonth)
            ->count();

        // Clientes únicos del mes
        $uniqueCustomers = Order::where('empresa_id', $empresa->Id_Empresa)
            ->where('created_at', '>=', $thisMonth)
            ->distinct('user_id')
            ->count();

        return [
            'total_sales' => $currentMonthSales,
            'sales_growth' => $salesGrowth,
            'discounted_products' => $discountedProducts,
            'expiring_products' => $expiringProducts,
            'monthly_orders' => $monthlyOrders,
            'unique_customers' => $uniqueCustomers,
            'total_products' => $empresa->productos()->count(),
            'active_discount_rules' => $empresa->discountRules()->where('is_active', true)->count(),
        ];
    }

    /**
     * Obtener datos de ventas para gráficos
     */
    private function getSalesData(Empresa $empresa): array
    {
        $last30Days = collect();
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->copy()->startOfDay();
            $dayEnd = $date->copy()->endOfDay();

            $sales = OrderItem::whereHas('order', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->Id_Empresa);
            })
            ->whereBetween('created_at', [$dayStart, $dayEnd])
            ->sum(DB::raw('precio * cantidad'));

            $orders = Order::where('empresa_id', $empresa->Id_Empresa)
                ->whereBetween('created_at', [$dayStart, $dayEnd])
                ->count();

            $last30Days->push([
                'date' => $date->format('Y-m-d'),
                'sales' => $sales,
                'orders' => $orders,
                'formatted_date' => $date->format('M d'),
            ]);
        }

        return $last30Days->toArray();
    }

    /**
     * Obtener productos más vendidos
     */
    private function getTopProducts(Empresa $empresa): array
    {
        return OrderItem::whereHas('order', function ($query) use ($empresa) {
            $query->where('empresa_id', $empresa->Id_Empresa);
        })
        ->select('producto_id', DB::raw('SUM(cantidad) as total_sold'), DB::raw('SUM(precio * cantidad) as total_revenue'))
        ->with('producto')
        ->groupBy('producto_id')
        ->orderBy('total_sold', 'desc')
        ->limit(10)
        ->get()
        ->map(function ($item) {
            return [
                'producto' => $item->producto,
                'total_sold' => $item->total_sold,
                'total_revenue' => $item->total_revenue,
                'discount_info' => $item->producto ? $item->producto->getDiscountInfo() : null,
            ];
        })
        ->toArray();
    }

    /**
     * Obtener análisis de descuentos
     */
    private function getDiscountAnalysis(Empresa $empresa): array
    {
        $products = $empresa->productos()->where('Cantidad', '>', 0)->get();
        
        $totalProducts = $products->count();
        $discountedProducts = $products->filter(function ($producto) {
            return $producto->hasDiscount();
        });

        $totalDiscountValue = $discountedProducts->sum(function ($producto) {
            $discountInfo = $producto->getDiscountInfo();
            return $discountInfo['discount_amount'] * $producto->Cantidad;
        });

        $averageDiscount = $discountedProducts->count() > 0 
            ? $discountedProducts->avg(function ($producto) {
                return $producto->getDiscountInfo()['discount_percentage'];
            })
            : 0;

        $discountRules = $empresa->discountRules()->where('is_active', true)->get();

        return [
            'total_products' => $totalProducts,
            'discounted_products' => $discountedProducts->count(),
            'discount_percentage' => $totalProducts > 0 ? ($discountedProducts->count() / $totalProducts) * 100 : 0,
            'total_discount_value' => $totalDiscountValue,
            'average_discount' => $averageDiscount,
            'active_rules' => $discountRules->count(),
            'rules' => $discountRules,
        ];
    }

    /**
     * Obtener productos próximos a caducar
     */
    private function getExpiringProducts(Empresa $empresa): array
    {
        return $empresa->productos()
            ->where('Cantidad', '>', 0)
            ->whereNotNull('Fecha_Caducidad')
            ->where('Fecha_Caducidad', '>', now())
            ->where('Fecha_Caducidad', '<=', now()->addDays(30))
            ->orderBy('Fecha_Caducidad')
            ->limit(20)
            ->get()
            ->map(function ($producto) {
                $discountInfo = $producto->getDiscountInfo();
                return [
                    'producto' => $producto,
                    'days_until_expiry' => $producto->getDaysUntilExpiry(),
                    'expiry_status' => $producto->getExpiryStatus(),
                    'discount_info' => $discountInfo,
                ];
            })
            ->toArray();
    }

    /**
     * Obtener estadísticas de geolocalización
     */
    private function getLocationStats(Empresa $empresa): array
    {
        $hasValidCoordinates = $empresa->hasValidCoordinates();
        
        if (!$hasValidCoordinates) {
            return [
                'has_coordinates' => false,
                'message' => 'Configura tu ubicación para aparecer en el mapa de ofertas',
            ];
        }

        // Simular datos de visitas por proximidad (en un caso real, esto vendría de analytics)
        $nearbyVisits = rand(50, 200);
        $mapViews = rand(100, 500);
        $locationBasedOrders = rand(10, 50);

        return [
            'has_coordinates' => true,
            'coordinates' => $empresa->getCoordinates(),
            'coverage_radius' => $empresa->coverage_radius,
            'nearby_visits' => $nearbyVisits,
            'map_views' => $mapViews,
            'location_based_orders' => $locationBasedOrders,
            'conversion_rate' => $mapViews > 0 ? ($locationBasedOrders / $mapViews) * 100 : 0,
        ];
    }

    /**
     * Obtener notificaciones recientes
     */
    private function getRecentNotifications(Empresa $empresa): array
    {
        return Notification::where('empresa_id', $empresa->Id_Empresa)
            ->with(['producto'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Obtener tendencias de ventas
     */
    private function getSalesTrends(Empresa $empresa): array
    {
        $last12Months = collect();
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $sales = OrderItem::whereHas('order', function ($query) use ($empresa) {
                $query->where('empresa_id', $empresa->Id_Empresa);
            })
            ->whereBetween('created_at', [$monthStart, $monthEnd])
            ->sum(DB::raw('precio * cantidad'));

            $orders = Order::where('empresa_id', $empresa->Id_Empresa)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();

            $last12Months->push([
                'month' => $date->format('Y-m'),
                'sales' => $sales,
                'orders' => $orders,
                'formatted_month' => $date->format('M Y'),
            ]);
        }

        return $last12Months->toArray();
    }

    /**
     * Obtener análisis de clientes
     */
    private function getCustomerAnalysis(Empresa $empresa): array
    {
        $totalCustomers = Order::where('empresa_id', $empresa->Id_Empresa)
            ->distinct('user_id')
            ->count();

        $repeatCustomers = Order::where('empresa_id', $empresa->Id_Empresa)
            ->select('user_id', DB::raw('COUNT(*) as order_count'))
            ->groupBy('user_id')
            ->having('order_count', '>', 1)
            ->count();

        $averageOrderValue = Order::where('empresa_id', $empresa->Id_Empresa)
            ->avg('total');

        $customerRetentionRate = $totalCustomers > 0 ? ($repeatCustomers / $totalCustomers) * 100 : 0;

        return [
            'total_customers' => $totalCustomers,
            'repeat_customers' => $repeatCustomers,
            'new_customers' => $totalCustomers - $repeatCustomers,
            'customer_retention_rate' => $customerRetentionRate,
            'average_order_value' => $averageOrderValue,
        ];
    }
}