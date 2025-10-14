<?php

namespace App\Http\Controllers;

use App\Models\DiscountRule;
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Subcategoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        Log::info('Intentando crear nueva regla de descuento', [
            'empresa_id' => $empresa ? $empresa->Id_Empresa : null,
        ]);

        // Límite máximo de reglas configurables
        $limit = (int) config('discount.rules_limit', 5);
        $currentCount = DiscountRule::byEmpresa($empresa->Id_Empresa)->count();
        if ($currentCount >= $limit) {
            Log::warning('Límite de reglas alcanzado', [
                'empresa_id' => $empresa->Id_Empresa,
                'current' => $currentCount,
                'limit' => $limit,
            ]);
            return redirect()->route('discount-rules.index')
                ->with('error', "Has alcanzado el límite de {$limit} reglas de descuento.");
        }

        try {
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
                'discount_value.min' => 'El valor del descuento debe ser mayor a 0.',
                'expires_at.after' => 'La fecha de expiración debe ser posterior a la de inicio.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $ve) {
            Log::warning('Validación fallida al crear regla de descuento', [
                'empresa_id' => $empresa->Id_Empresa,
                'errors' => $ve->errors(),
            ]);
            throw $ve;
        }

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return back()->withErrors(['discount_value' => 'El descuento porcentual no puede ser mayor al 100%.']);
        }

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

            Log::info('Regla de descuento creada exitosamente', [
                'empresa_id' => $empresa->Id_Empresa,
                'discount_rule_id' => $discountRule->id,
            ]);

            return redirect()->route('discount-rules.index')
                ->with('success', 'Regla de descuento creada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear la regla de descuento', [
                'empresa_id' => $empresa->Id_Empresa,
                'exception' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => 'Error al crear la regla: ' . $e->getMessage()]);
        }
    }

    /**
     * Mostrar una regla específica
     */
    public function show($id)
    {
        $empresa = Auth::guard('empresa')->user();
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)->findOrFail($id);

        $affectedProducts = Producto::where('Id_Empresa', $empresa->Id_Empresa)
            ->get()
            ->filter(fn($producto) => $discountRule->isApplicableToProduct($producto));

        $stats = $discountRule->getStats();

        return view('discount-rules.show', compact('discountRule', 'affectedProducts', 'stats'));
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit($id)
    {
        $empresa = Auth::guard('empresa')->user();
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)->findOrFail($id);

        $categorias = Categoria::with('subcategorias')->get();
        $subcategorias = Subcategoria::all();
        $productos = Producto::where('Id_Empresa', $empresa->Id_Empresa)
            ->select('Id_Producto', 'Nombre', 'Marca')
            ->get();

        return view('discount-rules.edit', compact('discountRule', 'categorias', 'subcategorias', 'productos', 'empresa'));
    }

    /**
     * Actualizar una regla existente
     */
    public function update(Request $request, $id)
    {
        $empresa = Auth::guard('empresa')->user();
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'days_before_expiry' => 'required|integer|min:1|max:30',
            'discount_type' => 'required|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0.01',
            'minimum_discount' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after:starts_at',
        ]);

        if ($request->discount_type === 'percentage' && $request->discount_value > 100) {
            return back()->withErrors(['discount_value' => 'El descuento porcentual no puede ser mayor al 100%.']);
        }

        if ($request->maximum_discount && $request->minimum_discount && $request->maximum_discount < $request->minimum_discount) {
            return back()->withErrors(['maximum_discount' => 'El descuento máximo no puede ser menor al mínimo.']);
        }

        try {
            DB::beginTransaction();

            $discountRule->update($request->only([
                'name', 'description', 'days_before_expiry', 'discount_type', 'discount_value',
                'minimum_discount', 'maximum_discount', 'minimum_product_price',
                'applicable_categories', 'applicable_products', 'excluded_products',
                'is_automatic', 'starts_at', 'expires_at'
            ]));

            DB::commit();

            return redirect()->route('discount-rules.show', $discountRule->id)
                ->with('success', 'Regla de descuento actualizada exitosamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Error al actualizar la regla: ' . $e->getMessage()]);
        }
    }

    /**
     * Eliminar una regla
     */
    public function destroy($id)
    {
        $empresa = Auth::guard('empresa')->user();
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)->findOrFail($id);

        try {
            $discountRule->delete();
            return redirect()->route('discount-rules.index')
                ->with('success', 'Regla de descuento eliminada exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al eliminar la regla: ' . $e->getMessage()]);
        }
    }

    /**
     * Activar o desactivar una regla
     */
    public function toggle($id)
    {
        $empresa = Auth::guard('empresa')->user();
        $discountRule = DiscountRule::byEmpresa($empresa->Id_Empresa)->findOrFail($id);

        $discountRule->update(['is_active' => !$discountRule->is_active]);

        $status = $discountRule->is_active ? 'activada' : 'desactivada';
        return redirect()->route('discount-rules.index')
            ->with('success', "Regla de descuento {$status} exitosamente.");
    }

    /**
     * Crear reglas por defecto
     */
    public function createDefaults()
    {
        $empresa = Auth::guard('empresa')->user();

        try {
            DiscountRule::createDefaultRules($empresa->Id_Empresa);
            return redirect()->route('discount-rules.index')
                ->with('success', 'Reglas por defecto creadas exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error al crear reglas por defecto: ' . $e->getMessage()]);
        }
    }

    /**
     * Obtener estadísticas
     */
    private function getStats(int $empresaId): array
    {
        $totalRules = DiscountRule::byEmpresa($empresaId)->count();
        $activeRules = DiscountRule::byEmpresa($empresaId)->active()->count();
        $totalUsage = DiscountRule::byEmpresa($empresaId)->sum('usage_count');
        $totalSavings = DiscountRule::byEmpresa($empresaId)->sum('total_savings');

        $productsWithDiscount = Producto::where('Id_Empresa', $empresaId)
            ->get()
            ->filter(fn($producto) => $producto->hasDiscount())
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
     * API: Obtener descuento de un producto
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
