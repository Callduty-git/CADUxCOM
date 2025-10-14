<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session as LaravelSession;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Producto;
use App\Models\Coupon;

class WompiController extends Controller
{
    /**
     * Inicia un pago con Wompi: crea orden pendiente y devuelve parámetros del widget.
     */
    public function start(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'payment_method' => 'required|in:credit_card,digital_wallet',
            'same_as_shipping' => 'nullable|boolean',
        ]);

        $cart = LaravelSession::get('cart', []);
        if (empty($cart)) {
            return response()->json(['success' => false, 'error' => 'Tu carrito está vacío.'], 400);
        }

        // Obtener productos y calcular totales
        $productIds = array_keys($cart);
        $products = Producto::with(['empresa', 'subcategoria'])
            ->whereIn('Id_Producto', $productIds)
            ->get()
            ->keyBy('Id_Producto');

        $subtotal = 0;
        $orderItems = [];

        foreach ($cart as $productId => $cartItem) {
            $product = $products[$productId] ?? null;
            if (!$product) {
                continue;
            }

            $quantity = max(1, (int)($cartItem['quantity'] ?? 1));
            $lineTotal = $quantity * (float) $product->Precio;
            $subtotal += $lineTotal;

            $orderItems[] = [
                'product_id' => $product->Id_Producto,
                'product_name' => $product->Nombre,
                'product_description' => $product->Descripcion,
                'quantity' => $quantity,
                'unit_price' => (float) $product->Precio,
                'line_total' => $lineTotal,
                'product_brand' => $product->Marca,
                'product_category' => $product->subcategoria?->categoria?->Nombre,
                'product_subcategory' => $product->subcategoria?->Nombre,
            ];
        }

        // Facturación
        if ($request->boolean('same_as_shipping')) {
            $billingData = [
                'billing_address' => $request->shipping_address,
                'billing_city' => $request->shipping_city,
                'billing_state' => $request->shipping_state,
                'billing_postal_code' => $request->shipping_postal_code,
                'billing_country' => $request->shipping_country,
            ];
        } else {
            $billingData = [
                'billing_address' => $request->billing_address ?: $request->shipping_address,
                'billing_city' => $request->billing_city ?: $request->shipping_city,
                'billing_state' => $request->billing_state ?: $request->shipping_state,
                'billing_postal_code' => $request->billing_postal_code ?: $request->shipping_postal_code,
                'billing_country' => $request->billing_country ?: $request->shipping_country,
            ];
        }

        if (empty($orderItems)) {
            return response()->json(['success' => false, 'error' => 'No hay productos válidos en tu carrito.'], 400);
        }

        // Totales (sin IVA ni costo de envío)
        $tax = 0;
        $shipping = 0;

        // Cupón
        $appliedCoupon = LaravelSession::get('applied_coupon');
        $couponDiscount = 0;
        $couponCode = null;

        if ($appliedCoupon) {
            $coupon = Coupon::byCode($appliedCoupon['code'])->valid()->first();
            if ($coupon && $coupon->canBeAppliedToAmount($subtotal)) {
                $couponDiscount = $appliedCoupon['discount'];
                $couponCode = $coupon->code;
                // Envío ya es 0 por política actual
            }
        }

        $total = $subtotal + $tax + $shipping - $couponDiscount;
        $amountInCents = (int) round($total * 100);

        // Crear orden PENDIENTE
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_state' => $request->shipping_state,
                'shipping_postal_code' => $request->shipping_postal_code,
                'shipping_country' => $request->shipping_country,
                'billing_address' => $billingData['billing_address'],
                'billing_city' => $billingData['billing_city'],
                'billing_state' => $billingData['billing_state'],
                'billing_postal_code' => $billingData['billing_postal_code'],
                'billing_country' => $billingData['billing_country'],
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $couponDiscount,
                'total_amount' => $total,
                'coupon_code' => $couponCode,
                'coupon_discount' => $couponDiscount,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'Error creando la orden: ' . $e->getMessage()], 500);
        }

        // Preparar datos del widget
        $reference = 'ORD-' . $order->id . '-' . time();
        $currency = 'COP';
        $secret = config('services.wompi.integrity_secret');
        $signature = hash('sha256', $reference . $amountInCents . $currency . $secret);

        // Guardar referencia para enlazar con el webhook
        $order->update([
            'payment_reference' => $reference,
        ]);

        return response()->json([
            'success' => true,
            'widget' => [
                'publicKey' => config('services.wompi.public_key'),
                'currency' => $currency,
                'amountInCents' => $amountInCents,
                'reference' => $reference,
                'integritySignature' => $signature,
                'redirectUrl' => config('services.wompi.redirect_url'),
            ],
        ]);
    }

    /**
     * Callback de redirección luego del pago en Wompi.
     * Nota: El estado final debe confirmarse vía webhook.
     */
    public function callback(Request $request)
    {
        // Mostrar un mensaje genérico; el webhook confirmará la orden.
        return redirect()->route('orders.index')
            ->with('success', 'Hemos recibido tu pago. Confirmaremos el estado en breve.');
    }

    /**
     * Webhook de Wompi para confirmar estado de transacción.
     */
    public function webhook(Request $request)
    {
        $payload = $request->json()->all();
        $transaction = $payload['data']['transaction'] ?? null;
        if (!$transaction) {
            return response()->json(['ok' => true]);
        }

        $reference = $transaction['reference'] ?? null;
        $status = $transaction['status'] ?? null; // e.g. APPROVED, DECLINED, VOIDED, ERROR, PENDING

        if ($reference) {
            $order = Order::where('payment_reference', $reference)->first();
            if ($order) {
                if ($status === 'APPROVED') {
                    $order->update([
                        'status' => Order::STATUS_PAID,
                        'paid_at' => now(),
                    ]);
                    // Limpiar carrito y cupón
                    LaravelSession::forget(['cart', 'applied_coupon']);
                } elseif (in_array($status, ['DECLINED', 'VOIDED', 'ERROR'])) {
                    $order->update([
                        'status' => Order::STATUS_CANCELLED,
                    ]);
                } else {
                    // PENDIENTE: mantener estado
                }
            }
        }

        return response()->json(['ok' => true]);
    }
}