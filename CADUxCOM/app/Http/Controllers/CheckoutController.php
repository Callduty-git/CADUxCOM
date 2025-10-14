<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

/**
 * Controlador CheckoutController - Maneja el proceso de checkout y creación de órdenes
 */
class CheckoutController extends Controller
{
    /**
     * Mostrar la página de checkout
     */
    public function index()
    {
        $cart = Session::get('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Obtener productos del carrito
        $productIds = array_keys($cart);
        $products = Producto::with(['empresa', 'subcategoria'])
            ->whereIn('Id_Producto', $productIds)
            ->get()
            ->keyBy('Id_Producto');

        $items = [];
        $subtotal = 0;
        $cartErrors = [];

        foreach ($cart as $productId => $cartItem) {
            $product = $products[$productId] ?? null;
            if (!$product) {
                unset($cart[$productId]);
                continue;
            }

            $quantity = max(1, (int)($cartItem['quantity'] ?? 1));

            // Verificar stock
            if ($product->Cantidad < $quantity) {
                $cartErrors[] = "Solo hay {$product->Cantidad} unidades disponibles de {$product->Nombre}";
                continue;
            }

            $lineTotal = $quantity * (float)$product->Precio;
            $subtotal += $lineTotal;

            $items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        // Actualizar carrito si hay productos eliminados
        if (count($cart) !== count($productIds)) {
            Session::put('cart', $cart);
        }

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'No hay productos válidos en tu carrito.');
        }

        // Calcular IVA y envío
        $tax = $subtotal * 0.19; // IVA 19%
        $shipping = $subtotal > 100000 ? 0 : 5000; // Envío gratis sobre $100,000

        $total = $subtotal + $tax + $shipping;

        // Datos del usuario autenticado
        $user = Auth::user();
        $userData = $user ? [
            'name' => $user->name,
            'email' => $user->email,
        ] : [];

        return view('checkout.index', compact(
            'items', 
            'subtotal', 
            'tax', 
            'shipping', 
            'total', 
            'userData',
            'cartErrors'
        ));
    }

    /**
     * Procesar el checkout y crear la orden
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city' => 'required|string|max:100',
            'shipping_state' => 'required|string|max:100',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'billing_address' => 'nullable|string|max:500',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_postal_code' => 'nullable|string|max:20',
            'billing_country' => 'nullable|string|max:100',
            'payment_method' => 'required|in:credit_card,debit_card,bank_transfer,cash_on_delivery,digital_wallet',
            'notes' => 'nullable|string|max:1000',
            'same_as_shipping' => 'nullable|boolean',
        ]);

        $cart = Session::get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Tu carrito está vacío.');
        }

        // Validar disponibilidad de productos
        $productIds = array_keys($cart);
        $products = Producto::with(['empresa', 'subcategoria'])
            ->whereIn('Id_Producto', $productIds)
            ->get()
            ->keyBy('Id_Producto');

        foreach ($cart as $productId => $cartItem) {
            $product = $products[$productId] ?? null;
            if (!$product || $product->Cantidad < 1) {
                return redirect()->route('cart.index')->with('error', 'Uno o más productos ya no están disponibles.');
            }
            $quantity = max(1, (int)($cartItem['quantity'] ?? 1));
            if ($product->Cantidad < $quantity) {
                return back()->with('error', "Solo hay {$product->Cantidad} unidades disponibles de {$product->Nombre}");
            }
        }

        // Dirección de facturación
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

        // Calcular totales
        $subtotal = 0;
        $orderItems = [];

        foreach ($cart as $productId => $cartItem) {
            $product = $products[$productId];
            $quantity = max(1, (int)($cartItem['quantity'] ?? 1));
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

        $tax = $subtotal * 0.19;
        $shipping = $subtotal > 100000 ? 0 : 5000;

        $total = $subtotal + $tax + $shipping;

        try {
            DB::beginTransaction();

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
                'discount_amount' => 0,
                'total_amount' => $total,
                'coupon_code' => null,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
            ]);

            foreach ($orderItems as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            // Reducir stock
            foreach ($orderItems as $item) {
                $product = Producto::find($item['product_id']);
                if ($product) {
                    $product->decrement('Cantidad', $item['quantity']);
                }
            }



            DB::commit();

            Session::forget('cart');

            return redirect()->route('orders.show', $order->id)
                ->with('success', '¡Orden creada exitosamente! Te hemos enviado un email de confirmación.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Hubo un error al procesar tu orden. Por favor, intenta nuevamente.')->withInput();
        }
    }
}
