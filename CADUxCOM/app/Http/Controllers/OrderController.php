<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controlador OrderController - Maneja las órdenes de compra
 *
 * Permite:
 * - Ver historial de órdenes
 * - Ver detalles de una orden
 * - Cancelar, reembolsar y marcar como recibida
 * - Reordenar productos de una orden anterior
 * - Descargar factura en PDF
 * - Obtener estadísticas de órdenes
 */
class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el historial de órdenes del usuario
     */
    public function index(Request $request)
    {
        $orders = Order::with(['items.product', 'items.empresa'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['orders' => $orders]);
        }

        return view('orders.index', compact('orders'));
    }

    /**
     * Mostrar los detalles de una orden específica
     */
    public function show($id, Request $request)
    {
        $order = Order::with(['items.product', 'items.empresa', 'user'])
            ->where('id', $id)
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('customer_email', Auth::user()->email);
            })
            ->first();

        if (!$order) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Orden no encontrada'], 404);
            }
            return redirect()->route('orders.index')->with('error', 'Orden no encontrada.');
        }

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json($order);
        }

        return view('orders.show', compact('order'));
    }

    /**
     * Cancelar una orden
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Orden no encontrada.');
        }

        if (!$order->canBeCancelled()) {
            return back()->with('error', 'Esta orden no puede ser cancelada.');
        }

        if ($order->status === Order::STATUS_REFUNDED) {
            return back()->with('error', 'No puedes cancelar una orden ya reembolsada.');
        }

        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'admin_notes' => 'Cancelada por el cliente: ' . ($request->reason ?? 'Sin razón especificada'),
        ]);

        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('Cantidad', $item->quantity);
            }
        }

        return back()->with('success', 'Orden cancelada exitosamente.');
    }

    /**
     * Solicitar reembolso de una orden
     */
    public function requestRefund(Request $request, $id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Orden no encontrada.');
        }

        if (!$order->canBeRefunded()) {
            return back()->with('error', 'Esta orden no puede ser reembolsada.');
        }

        if ($order->status === Order::STATUS_CANCELLED) {
            return back()->with('error', 'No puedes solicitar reembolso de una orden cancelada.');
        }

        $order->update([
            'status' => Order::STATUS_REFUNDED,
            'admin_notes' => 'Reembolso solicitado por el cliente: ' . ($request->reason ?? 'Sin razón especificada'),
        ]);

        return back()->with('success', 'Solicitud de reembolso enviada. Te contactaremos pronto.');
    }

    /**
     * Reordenar productos de una orden anterior
     */
    public function reorder($id)
    {
        $order = Order::with('items.product')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return back()->with('error', 'Orden no encontrada.');
        }

        $cart = session('cart', []);
        $addedCount = 0;
        $errors = [];

        foreach ($order->items as $item) {
            $product = $item->product;
            if (!$product) {
                $errors[] = "El producto {$item->product_name} ya no está disponible";
                continue;
            }

            if ($product->Cantidad <= 0) {
                $errors[] = "El producto {$product->Nombre} está agotado";
                continue;
            }

            $quantity = min($item->quantity, $product->Cantidad);

            if (isset($cart[$product->Id_Producto])) {
                $newQty = $cart[$product->Id_Producto]['quantity'] + $quantity;
                if ($newQty > $product->Cantidad) {
                    $errors[] = "No se pudo agregar {$product->Nombre}: stock insuficiente.";
                    continue;
                }
                $cart[$product->Id_Producto]['quantity'] = $newQty;
            } else {
                $cart[$product->Id_Producto] = [
                    'quantity' => $quantity,
                    'added_at' => now()->toDateTimeString()
                ];
            }

            $addedCount++;
        }

        session(['cart' => $cart]);

        $message = "Se agregaron {$addedCount} productos al carrito.";
        if (!empty($errors)) {
            $message .= " Algunos productos no pudieron ser agregados: " . implode(', ', $errors);
        }

        return redirect()->route('cart.index')->with('success', $message);
    }

    /**
     * Descargar factura de una orden
     */
    public function downloadInvoice($id)
    {
        $order = Order::with(['items.product', 'items.empresa'])
            ->where('id', $id)
            ->where(function ($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('customer_email', Auth::user()->email);
            })
            ->first();

        if (!$order) {
            abort(404, 'Orden no encontrada');
        }

        // TODO: Generar PDF de la factura (único formato soportado)
        return redirect()->route('orders.show', $order->id)
            ->with('info', 'La descarga de factura en PDF estará disponible pronto.');
    }

    /**
     * Obtener estadísticas de órdenes del usuario
     */
    public function getStats()
    {
        $userId = Auth::id();

        $stats = [
            'total_orders'     => Order::where('user_id', $userId)->count(),
            'pending_orders'   => Order::where('user_id', $userId)->where('status', Order::STATUS_PENDING)->count(),
            'completed_orders' => Order::where('user_id', $userId)->where('status', Order::STATUS_DELIVERED)->count(),
            'total_spent'      => Order::where('user_id', $userId)->where('status', Order::STATUS_DELIVERED)->sum('total_amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Marcar orden como recibida
     */
    public function markAsReceived($id)
    {
        $order = Order::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', Order::STATUS_SHIPPED)
            ->first();

        if (!$order) {
            return back()->with('error', 'Orden no encontrada o no está en estado de envío.');
        }

        $order->update([
            'status' => Order::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);

        return back()->with('success', 'Orden marcada como recibida. ¡Gracias por tu compra!');
    }
}
