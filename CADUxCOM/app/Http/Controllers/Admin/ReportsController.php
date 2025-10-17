<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Mostrar el dashboard principal de reportes
     */
    public function index()
    {
        // Estadísticas generales
        $totalUsers = User::count();
        $totalCompanies = Empresa::count();
        $totalProducts = Producto::count();
        
        // Estadísticas por período
        $thisMonth = Carbon::now()->startOfMonth();
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        
        $usersThisMonth = User::where('created_at', '>=', $thisMonth)->count();
        $companiesThisMonth = Empresa::where('created_at', '>=', $thisMonth)->count();
        $productsThisMonth = Producto::where('created_at', '>=', $thisMonth)->count();
        
        $usersLastMonth = User::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        $companiesLastMonth = Empresa::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        $productsLastMonth = Producto::whereBetween('created_at', [$lastMonth, $thisMonth])->count();
        
        // Calcular porcentajes de crecimiento
        $userGrowth = $usersLastMonth > 0 ? (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100 : 0;
        $companyGrowth = $companiesLastMonth > 0 ? (($companiesThisMonth - $companiesLastMonth) / $companiesLastMonth) * 100 : 0;
        $productGrowth = $productsLastMonth > 0 ? (($productsThisMonth - $productsLastMonth) / $productsLastMonth) * 100 : 0;
        
        // Estados de empresas
        $companyStats = Empresa::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        // Registros por día (últimos 30 días)
        $dailyRegistrations = $this->getDailyRegistrations();
        
        // Top empresas por productos
        $topCompanies = Empresa::withCount('productos')
            ->orderBy('productos_count', 'desc')
            ->limit(10)
            ->get();
        
        // Distribución de usuarios por rol
        $userRoles = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role')
            ->toArray();

        return view('admin.reports.index', compact(
            'totalUsers', 'totalCompanies', 'totalProducts',
            'usersThisMonth', 'companiesThisMonth', 'productsThisMonth',
            'userGrowth', 'companyGrowth', 'productGrowth',
            'companyStats', 'dailyRegistrations', 'topCompanies', 'userRoles'
        ));
    }

    /**
     * Reporte detallado de usuarios
     */
    public function users(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $role = $request->get('role');

        $query = User::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($role) {
            $query->where('role', $role);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Estadísticas del período
        $totalUsers = $query->count();
        $verifiedUsers = $query->whereNotNull('email_verified_at')->count();
        $adminUsers = $query->where('role', 'admin')->count();
        
        // Usuarios registrados este mes
        $thisMonthUsers = User::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();
        
        // Crear array de estadísticas para la vista
        $stats = [
            'total' => $totalUsers,
            'verified' => $verifiedUsers,
            'admins' => $adminUsers,
            'this_month' => $thisMonthUsers
        ];
        
        // Registros por día en el período
        $dailyRegistrations = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.reports.users', compact(
            'users', 'startDate', 'endDate', 'role', 'stats', 'dailyRegistrations'
        ));
    }

    /**
     * Reporte detallado de empresas
     */
    public function companies(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $status = $request->get('status');
        $category = $request->get('category');

        $query = Empresa::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($status) {
            $query->where('status', $status);
        }
        
        // Filtro por categoría removido - la tabla empresas no tiene columna categoria

        $companies = $query->withCount('productos')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Estadísticas del período
        $totalCompanies = $query->count();
        $approvedCompanies = $query->where('status', 'approved')->count();
        $pendingCompanies = $query->where('status', 'pending')->count();
        $rejectedCompanies = $query->where('status', 'rejected')->count();
        
        // Empresas registradas este mes
        $thisMonthCompanies = Empresa::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Crear array de estadísticas para la vista
        $stats = [
            'total' => $totalCompanies,
            'approved' => $approvedCompanies,
            'pending' => $pendingCompanies,
            'rejected' => $rejectedCompanies,
            'this_month' => $thisMonthCompanies
        ];
        
        // Empresas por categoría - removido porque la tabla empresas no tiene columna categoria
        $categoriesStats = collect(); // Array vacío
        
        // Categorías disponibles para el filtro - removido porque la tabla empresas no tiene columna categoria
        $categories = collect(); // Array vacío
        
        // Registros diarios de empresas para el gráfico
        $dailyRegistrations = Empresa::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count
                ];
            });

        return view('admin.reports.companies', compact(
            'companies', 'startDate', 'endDate', 'status', 'category',
            'stats', 'categoriesStats', 'categories', 'dailyRegistrations'
        ));
    }

    /**
     * Reporte detallado de productos
     */
    public function products(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        $query = Producto::whereBetween('created_at', [$startDate, $endDate]);
        
        if ($minPrice) {
            $query->where('Precio', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $query->where('Precio', '<=', $maxPrice);
        }

        $products = $query->with('empresa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Estadísticas del período
        $totalProducts = $query->count();
        $averagePrice = $query->avg('Precio');
        $maxProductPrice = $query->max('Precio');
        $minProductPrice = $query->min('Precio');
        
        // Productos registrados este mes
        $thisMonthProducts = Producto::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();
        
        // Crear array de estadísticas para la vista
        $stats = [
            'total' => $totalProducts,
            'average_price' => $averagePrice ?: 0,
            'max_price' => $maxProductPrice ?: 0,
            'min_price' => $minProductPrice ?: 0,
            'this_month' => $thisMonthProducts
        ];
        
        // Productos por rango de precio
        $priceRanges = [
            '0-50000' => $query->where('Precio', '<=', 50000)->count(),
            '50001-100000' => $query->whereBetween('Precio', [50001, 100000])->count(),
            '100001-500000' => $query->whereBetween('Precio', [100001, 500000])->count(),
            '500001+' => $query->where('Precio', '>', 500000)->count(),
        ];

        // Registros diarios de productos para el gráfico
        $dailyRegistrations = Producto::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'count' => $item->count
                ];
            });

        return view('admin.reports.products', compact(
            'products', 'startDate', 'endDate', 'minPrice', 'maxPrice',
            'stats', 'priceRanges', 'dailyRegistrations'
        ));
    }

    /**
     * Exportar reporte en CSV
     */
    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));

        $filename = "reporte_{$type}_{$startDate}_al_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function() use ($type, $startDate, $endDate) {
            $handle = fopen('php://output', 'w');
            
            switch ($type) {
                case 'users':
                    $this->exportUsers($handle, $startDate, $endDate);
                    break;
                case 'companies':
                    $this->exportCompanies($handle, $startDate, $endDate);
                    break;
                case 'products':
                    $this->exportProducts($handle, $startDate, $endDate);
                    break;
            }
            
            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Obtener registros diarios para gráficos
     */
    private function getDailyRegistrations()
    {
        $days = 30;
        $startDate = Carbon::now()->subDays($days);
        
        $users = User::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
        
        $companies = Empresa::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();
        
        $products = Producto::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        // Generar array con todos los días
        $result = [];
        for ($i = $days; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $result[] = [
                'date' => $date,
                'users' => $users[$date] ?? 0,
                'companies' => $companies[$date] ?? 0,
                'products' => $products[$date] ?? 0,
            ];
        }

        return $result;
    }

    /**
     * Exportar usuarios a CSV
     */
    private function exportUsers($handle, $startDate, $endDate)
    {
        // Encabezados
        fputcsv($handle, ['ID', 'Nombre', 'Email', 'Rol', 'Email Verificado', 'Fecha de Registro']);
        
        // Datos
        User::whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->chunk(1000, function($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->role,
                        $user->email_verified_at ? 'Sí' : 'No',
                        $user->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });
    }

    /**
     * Exportar empresas a CSV
     */
    private function exportCompanies($handle, $startDate, $endDate)
    {
        // Encabezados
        fputcsv($handle, ['ID', 'Nombre', 'Email', 'NIT', 'Estado', 'Categoría', 'Productos', 'Fecha de Registro']);
        
        // Datos
        Empresa::whereBetween('created_at', [$startDate, $endDate])
            ->withCount('productos')
            ->orderBy('created_at', 'desc')
            ->chunk(1000, function($companies) use ($handle) {
                foreach ($companies as $company) {
                    fputcsv($handle, [
                        $company->id,
                        $company->Nombre,
                        $company->email,
                        $company->NIT,
                        $company->status,
                        $company->categoria,
                        $company->productos_count,
                        $company->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });
    }

    /**
     * Exportar productos a CSV
     */
    private function exportProducts($handle, $startDate, $endDate)
    {
        // Encabezados
        fputcsv($handle, ['ID', 'Nombre', 'Precio', 'Empresa', 'Fecha de Registro']);
        
        // Datos
        Producto::whereBetween('created_at', [$startDate, $endDate])
            ->with('empresa')
            ->orderBy('created_at', 'desc')
            ->chunk(1000, function($products) use ($handle) {
                foreach ($products as $product) {
                    fputcsv($handle, [
                        $product->id,
                        $product->Nombre,
                        $product->Precio,
                        $product->empresa->Nombre ?? 'N/A',
                        $product->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });
    }
}