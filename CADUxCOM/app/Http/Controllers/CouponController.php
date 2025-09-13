<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador CouponController - Maneja los cupones de descuento
 * 
 * Este controlador permite validar y aplicar cupones de descuento
 * durante el proceso de checkout.
 */
class CouponController extends Controller
{
    /**
     * Validar un cupón de descuento
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:productos,Id_Producto',
        ]);

        $code = strtoupper(trim($request->code));
        $subtotal = (float) $request->subtotal;
        $productIds = $request->product_ids ?? [];

        // Buscar el cupón
        $coupon = Coupon::byCode($code)->valid()->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Cupón no válido o expirado',
            ], 400);
        }

        // Verificar monto mínimo
        if (!$coupon->canBeAppliedToAmount($subtotal)) {
            return response()->json([
                'valid' => false,
                'message' => "El cupón requiere un monto mínimo de $" . number_format($coupon->minimum_amount, 0, ',', '.'),
            ], 400);
        }

        // Verificar productos aplicables
        if (!empty($productIds)) {
            foreach ($productIds as $productId) {
                if (!$coupon->isApplicableToProduct($productId)) {
                    return response()->json([
                        'valid' => false,
                        'message' => 'Este cupón no es aplicable a algunos productos en tu carrito',
                    ], 400);
                }
            }
        }

        // Calcular descuento
        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return response()->json([
                'valid' => false,
                'message' => 'No se puede aplicar descuento con este cupón',
            ], 400);
        }

        return response()->json([
            'valid' => true,
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'formatted_value' => $coupon->formatted_value,
                'discount' => $discount,
                'formatted_discount' => '$' . number_format($discount, 0, ',', '.'),
                'is_free_shipping' => $coupon->type === Coupon::TYPE_FREE_SHIPPING,
            ],
            'message' => 'Cupón aplicado exitosamente',
        ]);
    }

    /**
     * Aplicar un cupón al carrito
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:productos,Id_Producto',
        ]);

        $code = strtoupper(trim($request->code));
        $subtotal = (float) $request->subtotal;
        $productIds = $request->product_ids ?? [];

        // Buscar el cupón
        $coupon = Coupon::byCode($code)->valid()->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Cupón no válido o expirado',
            ], 400);
        }

        // Verificar monto mínimo
        if (!$coupon->canBeAppliedToAmount($subtotal)) {
            return response()->json([
                'success' => false,
                'message' => "El cupón requiere un monto mínimo de $" . number_format($coupon->minimum_amount, 0, ',', '.'),
            ], 400);
        }

        // Verificar productos aplicables
        if (!empty($productIds)) {
            foreach ($productIds as $productId) {
                if (!$coupon->isApplicableToProduct($productId)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Este cupón no es aplicable a algunos productos en tu carrito',
                    ], 400);
                }
            }
        }

        // Calcular descuento
        $discount = $coupon->calculateDiscount($subtotal);

        if ($discount <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede aplicar descuento con este cupón',
            ], 400);
        }

        // Guardar cupón en la sesión
        session([
            'applied_coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
                'type' => $coupon->type,
                'is_free_shipping' => $coupon->type === Coupon::TYPE_FREE_SHIPPING,
            ]
        ]);

        return response()->json([
            'success' => true,
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'formatted_value' => $coupon->formatted_value,
                'discount' => $discount,
                'formatted_discount' => '$' . number_format($discount, 0, ',', '.'),
                'is_free_shipping' => $coupon->type === Coupon::TYPE_FREE_SHIPPING,
            ],
            'message' => 'Cupón aplicado exitosamente',
        ]);
    }

    /**
     * Remover cupón aplicado
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function remove(Request $request)
    {
        session()->forget('applied_coupon');

        return response()->json([
            'success' => true,
            'message' => 'Cupón removido exitosamente',
        ]);
    }

    /**
     * Obtener cupón aplicado actualmente
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getApplied()
    {
        $appliedCoupon = session('applied_coupon');

        if (!$appliedCoupon) {
            return response()->json([
                'applied' => false,
                'message' => 'No hay cupón aplicado',
            ]);
        }

        $coupon = Coupon::byCode($appliedCoupon['code'])->first();

        if (!$coupon || !$coupon->isValid()) {
            // Remover cupón inválido de la sesión
            session()->forget('applied_coupon');
            
            return response()->json([
                'applied' => false,
                'message' => 'El cupón aplicado ya no es válido',
            ]);
        }

        return response()->json([
            'applied' => true,
            'coupon' => [
                'code' => $coupon->code,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'value' => $coupon->value,
                'formatted_value' => $coupon->formatted_value,
                'discount' => $appliedCoupon['discount'],
                'formatted_discount' => '$' . number_format($appliedCoupon['discount'], 0, ',', '.'),
                'is_free_shipping' => $appliedCoupon['is_free_shipping'],
            ],
        ]);
    }

    /**
     * Obtener cupones disponibles para el usuario
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAvailable()
    {
        $coupons = Coupon::valid()
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($coupon) {
                return [
                    'code' => $coupon->code,
                    'name' => $coupon->name,
                    'description' => $coupon->description,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'formatted_value' => $coupon->formatted_value,
                    'minimum_amount' => $coupon->minimum_amount,
                    'formatted_minimum_amount' => '$' . number_format($coupon->minimum_amount, 0, ',', '.'),
                    'expires_at' => $coupon->expires_at?->format('d/m/Y'),
                    'days_until_expiration' => $coupon->days_until_expiration,
                ];
            });

        return response()->json([
            'coupons' => $coupons,
        ]);
    }

    /**
     * Verificar si un cupón es válido para un producto específico
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkProduct(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ]);

        $code = strtoupper(trim($request->code));
        $productId = (int) $request->product_id;

        $coupon = Coupon::byCode($code)->valid()->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => 'Cupón no válido',
            ]);
        }

        $isApplicable = $coupon->isApplicableToProduct($productId);

        return response()->json([
            'valid' => $isApplicable,
            'message' => $isApplicable ? 'Cupón aplicable' : 'Este cupón no es aplicable a este producto',
        ]);
    }
}