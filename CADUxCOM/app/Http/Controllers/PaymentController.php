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
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class PaymentController extends Controller
{
    /**
     * Crear una sesión de Stripe Checkout y opcionalmente una orden pendiente.
     */
    public function createStripeSession(Request $request)
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
            if ($product->Cantidad < $quantity) {
                return response()->json(['success' => false, 'error' => "Solo hay {$product->Cantidad} unidades disponibles de {$product->Nombre}"], 400);
            }

            $lineTotal = $quantity * (float)$product->Precio;
            $subtotal += $lineTotal;

            $orderItems[] = [
                'product_id' => $product->Id_Producto,
                'product_name' => $product->Nombre,
                'product_sku' => $product->Codigo,
                'product_description' => $product->Descripcion,
                'empresa_id' => $product->Id_Empresa,
                'empresa_name' => $product->empresa->Nombre,
                'quantity' => $quantity,
                'unit_price' => (float)$product->Precio,
                'total_price' => $lineTotal,
                'product_image' => $product->Foto,
                'product_brand' => $product->Marca,
                'product_category' => $product->subcategoria->categoria->Nombre ?? null,
                'product_subcategory' => $product->subcategoria->Nombre ?? null,
            ];
        }

        if (empty($orderItems)) {
            return response()->json(['success' => false, 'error' => 'No hay productos válidos en tu carrito.'], 400);
        }

        // Política actual: sin IVA ni costo de envío
        $tax = 0;
        $shipping = 0;

        // Aplicar cupón si existe
        $appliedCoupon = LaravelSession::get('applied_coupon');
        $couponDiscount = 0;
        $couponCode = null;
        $freeShipping = false;

        if ($appliedCoupon) {
            $coupon = Coupon::byCode($appliedCoupon['code'])->valid()->first();
            if ($coupon && $coupon->canBeAppliedToAmount($subtotal)) {
                $couponDiscount = $appliedCoupon['discount'];
                $couponCode = $coupon->code;
                $freeShipping = $appliedCoupon['is_free_shipping'] ?? false;
                // Envío ya es 0 por política actual
            } else {
                LaravelSession::forget('applied_coupon');
            }
        }

        $total = $subtotal + $tax + $shipping - $couponDiscount;

        // Preparar datos de facturación
        $billingData = $request->same_as_shipping ? [
            'billing_address' => $request->shipping_address,
            'billing_city' => $request->shipping_city,
            'billing_state' => $request->shipping_state,
            'billing_postal_code' => $request->shipping_postal_code,
            'billing_country' => $request->shipping_country,
        ] : [
            'billing_address' => $request->billing_address ?: $request->shipping_address,
            'billing_city' => $request->billing_city ?: $request->shipping_city,
            'billing_state' => $request->billing_state ?: $request->shipping_state,
            'billing_postal_code' => $request->billing_postal_code ?: $request->shipping_postal_code,
            'billing_country' => $request->billing_country ?: $request->shipping_country,
        ];

        // Crear orden PENDIENTE sin decrementar stock todavía
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

        // Crear sesión de Stripe Checkout
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            $lineItems = [];
            foreach ($order->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'cop',
                        'product_data' => [
                            'name' => $item->product_name,
                            'description' => $item->product_description,
                        ],
                        'unit_amount' => (int) round($item->unit_price * 100),
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Agregar envío y descuentos como items separados si aplica
            if ($shipping > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'cop',
                        'product_data' => [
                            'name' => 'Envío',
                        ],
                        'unit_amount' => (int) round($shipping * 100),
                    ],
                    'quantity' => 1,
                ];
            }
            if ($couponDiscount > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'cop',
                        'product_data' => [
                            'name' => 'Descuento aplicado',
                        ],
                        'unit_amount' => (int) round($couponDiscount * -100),
                    ],
                    'quantity' => 1,
                ];
            }

            $session = StripeSession::create([
                'mode' => 'payment',
                'customer_email' => $order->customer_email,
                'line_items' => $lineItems,
                'client_reference_id' => (string) $order->id,
                'success_url' => route('payments.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.stripe.cancel', ['order' => $order->id]),
            ]);

            // Guardar referencia
            $order->update([
                'payment_reference' => $session->id,
            ]);

            return response()->json(['success' => true, 'url' => $session->url]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Error creando sesión de pago: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Página de éxito después de Stripe. Marca orden como pagada si aún está pendiente.
     */
    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        if (!$sessionId) {
            return redirect()->route('orders.index')->with('error', 'Sesión de pago no encontrada.');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            $orderId = $session->client_reference_id;
            $order = Order::find($orderId);
            if ($order && $order->status === Order::STATUS_PENDING) {
                $order->update([
                    'status' => Order::STATUS_PAID,
                    'paid_at' => now(),
                ]);

                // Limpiar carrito y cupón aplicado
                LaravelSession::forget(['cart', 'applied_coupon']);
            }
            return redirect()->route('orders.show', $orderId)->with('success', 'Pago confirmado. ¡Gracias por tu compra!');
        } catch (\Exception $e) {
            return redirect()->route('orders.index')->with('error', 'Error validando el pago: ' . $e->getMessage());
        }
    }

    /**
     * Cancelación de Stripe: deja la orden pendiente y permite reintentar.
     */
    public function cancel(Request $request)
    {
        $orderId = $request->query('order');
        if ($orderId) {
            return redirect()->route('orders.show', $orderId)->with('error', 'El pago fue cancelado. Puedes reintentar cuando quieras.');
        }
        return redirect()->route('checkout.index')->with('error', 'El pago fue cancelado.');
    }

    /**
     * Webhook para Stripe (opcional): marca la orden como pagada al completar.
     */
    public function webhook(Request $request)
    {
        $endpointSecret = config('services.stripe.webhook_secret');
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Payload inválido'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Firma inválida'], 400);
        }

        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->client_reference_id ?? null;
            if ($orderId) {
                $order = Order::find($orderId);
                if ($order && $order->status === Order::STATUS_PENDING) {
                    $order->update([
                        'status' => Order::STATUS_PAID,
                        'paid_at' => now(),
                    ]);
                }
            }
        }

        return response()->json(['received' => true]);
    }
}