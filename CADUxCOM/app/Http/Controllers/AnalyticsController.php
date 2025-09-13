<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Producto;
use App\Models\Review;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

/**
 * Controlador AnalyticsController - Dashboard de analytics para empresas
 */
class AnalyticsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:empresa');
    }

    public function index()
    {
        $empresa = Auth::guard('empresa')->user();
        
        $stats = $this->getGeneralStats($empresa->Id_Empresa);
        $salesData = $this->getSalesData($empresa->Id_Empresa);
        $topProducts = $this->getTopProducts($empresa->Id_Empresa);
        $recentReviews = $this->getRecentReviews($empresa->Id_Empresa);

        return view('analytics.dashboard', compact(
            'empresa', 
            'stats', 
            'salesData', 
            'topProducts', 
            'recentReviews'
        ));
    }

    private function getGeneralStats(int $empresaId): array
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        $todaySales = Order::whereHas('items', function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })
        ->whereDate('created_at', $today)
        ->where('status', '!=', Order::STATUS_CANCELLED)
        ->sum('total_amount');

        $thisMonthSales = Order::whereHas('items', function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })
        ->where('created_at', '>=', $thisMonth)
        ->where('status', '!=', Order::STATUS_CANCELLED)
        ->sum('total_amount');

        $totalProducts = Producto::where('Id_Empresa', $empresaId)->count();
        $activeProducts = Producto::where('Id_Empresa', $empresaId)
            ->where('Cantidad', '>', 0)
            ->count();

        $totalOrders = Order::whereHas('items', function ($query) use ($empresaId) {
            $query->where('empresa_id', $empresaId);
        })->count();

        $averageRating = Review::whereHas('product', function ($query) use ($empresaId) {
            $query->where('Id_Empresa', $empresaId);
        })
        ->approved()
        ->avg('rating');

        return [
            'today_sales' => $todaySales,
            'this_month_sales' => $thisMonthSales,
            'total_products' => $totalProducts,
            'active_products' => $activeProducts,
            'total_orders' => $totalOrders,
            'average_rating' => round($averageRating, 1),
        ];
    }

    private function getSalesData(int $empresaId): array
    {
        $last30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $sales = Order::whereHas('items', function ($query) use ($empresaId) {
                $query->where('empresa_id', $empresaId);
            })
            ->whereDate('created_at', $date)
            ->where('status', '!=', Order::STATUS_CANCELLED)
            ->sum('total_amount');

            $last30Days[] = [
                'date' => $date->format('Y-m-d'),
                'sales' => $sales,
                'formatted_date' => $date->format('d/m'),
            ];
        }

        return ['daily' => $last30Days];
    }

    private function getTopProducts(int $empresaId)
    {
        return Producto::where('Id_Empresa', $empresaId)
            ->withCount(['reviews as reviews_count' => function ($query) {
                $query->where('status', Review::STATUS_APPROVED);
            }])
            ->withAvg(['reviews as avg_rating' => function ($query) {
                $query->where('status', Review::STATUS_APPROVED);
            }], 'rating')
            ->with(['subcategoria'])
            ->orderBy('reviews_count', 'desc')
            ->limit(10)
            ->get();
    }

    private function getRecentReviews(int $empresaId)
    {
        return Review::whereHas('product', function ($query) use ($empresaId) {
            $query->where('Id_Empresa', $empresaId);
        })
        ->with(['user', 'product'])
        ->latest()
        ->limit(5)
        ->get();
    }

    public function getSalesChart(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();
        $period = $request->get('period', '30days');

        $data = [];
        $labels = [];

        if ($period === '30days') {
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $sales = Order::whereHas('items', function ($query) use ($empresa) {
                    $query->where('empresa_id', $empresa->Id_Empresa);
                })
                ->whereDate('created_at', $date)
                ->where('status', '!=', Order::STATUS_CANCELLED)
                ->sum('total_amount');

                $data[] = $sales;
                $labels[] = $date->format('d/m');
            }
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}