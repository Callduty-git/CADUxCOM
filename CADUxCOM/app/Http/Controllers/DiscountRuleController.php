<?php

namespace App\Http\Controllers;

use App\Models\DiscountRule;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Controlador DiscountRuleController - Maneja las reglas de descuentos progresivos
 */
class DiscountRuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:empresa');
    }

    /**
     * Mostrar todas las reglas de descuento de la empresa
     */
    public function index()
    {
        $empresa = Auth::guard('empresa')->user();
        
        $discountRules = DiscountRule::byEmpresa($empresa->Id_Empresa)
            ->orderBy('days_before_expiry', 'asc')
            ->paginate(10);

        $stats = $this->getStats($empresa->Id_Empresa);

        return view('discount-rules.index', compact('discountRules', 'stats', 'empresa'));
    }

    /**
     * Mostrar formulario para crear una nueva regla
     */
    public function create()
    {
        $empresa = Auth::guard('empresa')->user();
        
        $categorias = Categoria::with('subcategorias')->get();
        $subcategorias = Subcategoria::all();
        
        $productos = Producto::where('Id_Empresa', $empresa->Id_Empresa)
            ->select('Id_Producto', 'Nombre', 'Marca')
            ->get();

        return view('discount-rules.create', compact('categorias', 'subcategorias', 'productos', 'empresa'));
    }

    /**
     * Almacenar una nueva regla de descuento
     */
    public function store(Request $request)
    {
        $empresa = Auth::guard('empresa')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'days_before_expiry' => 'required|integer|min:1|max:30',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0.01',
            'minimum_discount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'minimum_product_price' => 'nullable|numeric|min:0',
            'applicable_categories' => 'nullable|array',
            'applicable_products' => 'nullable|array',
            'excluded_products' => 'nullable|array',
            'is_automatic' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:starts_at',
        ], [
            'name.required' => 'El nombre de la regla es obligatorio.',
            'days_before_expiry.required' => 'Los días antes de caducar son obligatorios.',
            'days_before_expiry.min' => 'Debe ser al menos 1 día.',
            'days_before_expiry.max' => 'No puede ser más de 30 días.',
            'discount_type.required' => 'El tipo de descuento es obligatorio.',
            'discount_type.in' => 'El tipo de descuento no es válido.',
            'discount_value.required' => 'El valor del descuento es obligatorio.',
            'discount_value.min' => 'El valor del descuento debe ser mayor a 0.',
            'expires_at.after' => 'La fecha de expiración debe ser posterior a la de inicio.'
        ]);

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return back()->withErrors(['discount_value' => 'El descuento porcentual no puede ser mayor al 100%.']);
        }

        // Validación extra: el descuento máximo no puede ser menor al mínimo
        if ($request->maximum_discount !== null && $request->minimum_discount !== null && $request->maximum_discount < $request->minimum_discount) {
            return back()->withErrors(['maximum_discount' => 'El descuento máximo no puede ser menor al mínimo.']);
        }

        try {
            DB::beginTransaction();

            $discountRule = DiscountRule::create([
                'empresa_id' => $empresa->Id_Empresa,
                'name' => $request->name,
                'description' => $request->description,
                'days_before_expiry' => $request->days_before_expiry,
                'discount_type' => $request->discount_type,
                'discount_value' => $request->discount_value,
                'minimum_discount' => $request->minimum_discount ?? 0,
                'maximum_discount' => $request->maximum_discount,
                'minimum_product_price' => $request->minimum_product_price ?? 0,
                'applicable_categories' => $request->applicable_categories,
                'applicable_products' => $request->applicable_products,
                'excluded_products' => $request->excluded_products,
                'is_automatic' => $request->has('is_automatic'),
                'starts_at' => $request->starts_at,
                'expires_at' => $request->expires_at,
            ]);

            DB::commit();

            return redirect()->route('discount-rules.index')
                ->with('success', 'Regla de descuento creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al crear la regla de descuento: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar una regla específica
     */
    public function show($id)
    {
        $empresa = Auth::guard('empresa')->user();
        
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)
            ->findOrFail($id);

        $affectedProducts = Producto::where('Id_Empresa', $empresa->Id_Empresa)
            ->get()
            ->filter(function ($producto) use ($discountRule) {
                return $discountRule->isApplicableToProduct($producto);
            });

        $stats = $discountRule->getStats();

        return view('discount-rules.show', compact('discountRule', 'affectedProducts', 'stats'));
    }

    /**
     * Eliminar una regla de descuento
     */
    public function destroy($id)
    {
        $empresa = Auth::guard('empresa')->user();
        
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)
            ->findOrFail($id);

        try {
            $discountRule->delete();

            return redirect()->route('discount-rules.index')
                ->with('success', 'Regla de descuento eliminada exitosamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la regla de descuento: ' . $e->getMessage()]);
        }
    }

    /**
     * Activar/desactivar una regla de descuento
     */
    public function toggle($id)
    {
        $empresa = Auth::guard('empresa')->user();
        
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)
            ->findOrFail($id);

        $discountRule->update(['is_active' => !$discountRule->is_active]);

        $status = $discountRule->is_active ? 'activada' : 'desactivada';

        return redirect()->route('discount-rules.index')
            ->with('success', "Regla de descuento {$status} exitosamente.");
    }

    /**
     * Crear reglas por defecto para la empresa
     */
    public function createDefaults()
    {
        $empresa = Auth::guard('empresa')->user();

        try {
            DiscountRule::createDefaultRules($empresa->Id_Empresa);

            return redirect()->route('discount-rules.index')
                ->with('success', 'Reglas de descuento por defecto creadas exitosamente.');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear las reglas por defecto: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener estadísticas de descuentos para la empresa
     */
    private function getStats(int $empresaId): array
    {
        $totalRules = DiscountRule::byEmpresa($empresaId)->count();
        $activeRules = DiscountRule::byEmpresa($empresaId)->active()->count();
        $totalUsage = DiscountRule::byEmpresa($empresaId)->sum('usage_count');
        $totalSavings = DiscountRule::byEmpresa($empresaId)->sum('total_savings');

        $productsWithDiscount = Producto::where('Id_Empresa', $empresaId)
            ->get()
            ->filter(function ($producto) {
                return $producto->hasDiscount();
            })
            ->count();

        return [
            'total_rules' => $totalRules,
            'active_rules' => $activeRules,
            'total_usage' => $totalUsage,
            'total_savings' => $totalSavings,
            'products_with_discount' => $productsWithDiscount,
        ];
    }

    /**
     * API: Obtener descuento para un producto específico
     */
    public function getProductDiscount(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ]);

        $producto = Producto::findOrFail($request->product_id);
        $discountInfo = $producto->getDiscountInfo();

        return response()->json([
            'success' => true,
            'data' => $discountInfo,
        ]);
    }
}