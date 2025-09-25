<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\Empresa;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return view('cart.index', [
                'items' => [],
                'total' => 0,
                'subtotal' => 0,
                'tax' => 0,
                'shipping' => 0
            ]);
        }

        // Estructura: [product_id => ['quantity' => N]]
        $productIds = array_keys($cart);
        $products = Producto::with(['empresa', 'subcategoria'])
            ->whereIn('Id_Producto', $productIds)
            ->get()
            ->keyBy('Id_Producto');

        $items = [];
        $subtotal = 0;
        
        foreach ($cart as $pid => $row) {
            $product = $products[$pid] ?? null;
            if (!$product) { 
                // Producto no encontrado, lo removemos del carrito
                unset($cart[$pid]);
                continue; 
            }
            
            $qty = max(1, (int)($row['quantity'] ?? 1));
            $lineTotal = $qty * (float)$product->Precio;
            $subtotal += $lineTotal;
            
            $items[] = [
                'product' => $product,
                'quantity' => $qty,
                'line_total' => $lineTotal,
                'unit_price' => (float)$product->Precio,
                'original_price' => (float)$product->PrecioOriginal,
                'discount' => (float)$product->PrecioOriginal - (float)$product->Precio,
            ];
        }

        // Actualizar carrito si se removieron productos
        if (count($cart) !== count($productIds)) {
            session()->put('cart', $cart);
        }

        // Cálculos de totales
        $tax = $subtotal * 0.19; // IVA 19%
        $shipping = $subtotal > 100000 ? 0 : 5000; // Envío gratis sobre $100,000
        $total = $subtotal + $tax + $shipping;

        return view('cart.index', compact('items', 'total', 'subtotal', 'tax', 'shipping'));
    }

    public function add(Request $request)
    {
        if (!Auth::check()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Debes iniciar sesión para agregar productos al carrito',
                    'redirect' => route('login')
                ], 401);
            }
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para agregar productos al carrito.');
        }

        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:productos,Id_Producto'],
            'quantity' => ['nullable', 'integer', 'min:1']
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad mínima es 1.'
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);
        if ($quantity < 1) $quantity = 1;

        $product = Producto::find($productId);
        if (!$product) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Producto no encontrado'], 404);
            }
            return back()->with('error', 'Producto no encontrado.');
        }
        if ($product->Cantidad <= 0) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Este producto está agotado'], 400);
            }
            return back()->with('error', 'Este producto está agotado.');
        }
        if ($product->Cantidad < $quantity) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No puedes agregar más de la cantidad disponible de este producto. Solo quedan ' . $product->Cantidad . ' unidades'
                ], 400);
            }
            return back()->with('error', 'No puedes agregar más de la cantidad disponible de este producto.');
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $newQuantity = max(1, (int)$cart[$productId]['quantity']) + $quantity;
            if ($newQuantity > $product->Cantidad) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'error' => 'No puedes agregar más de la cantidad disponible de este producto. Stock disponible: ' . $product->Cantidad . ' unidades'
                    ], 400);
                }
                return back()->with('error', 'No puedes agregar más de la cantidad disponible de este producto.');
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }
        session()->put('cart', $cart);
        if ($request->wantsJson() || $request->ajax()) {
            $count = array_sum(array_map(function($row) {
                return max(1, (int)($row['quantity'] ?? 1));
            }, $cart));
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => 'Producto agregado al carrito'
            ]);
        }
        return back()->with('success', 'Producto agregado al carrito exitosamente.');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:productos,Id_Producto'],
            'quantity' => ['required', 'integer', 'min:1']
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.',
            'quantity.required' => 'La cantidad es obligatoria.',
            'quantity.integer' => 'La cantidad debe ser un número entero.',
            'quantity.min' => 'La cantidad mínima es 1.'
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) $validated['quantity'];
        if ($quantity < 1) $quantity = 1;

        $product = Producto::find($productId);
        if (!$product) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Producto no encontrado'], 404);
            }
            return back()->with('error', 'Producto no encontrado.');
        }
        if ($product->Cantidad < $quantity) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'No puedes seleccionar más de la cantidad disponible. Solo quedan ' . $product->Cantidad . ' unidades'
                ], 400);
            }
            return back()->with('error', 'No puedes seleccionar más de la cantidad disponible.');
        }
        $cart = session('cart', []);
        if (!isset($cart[$productId])) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'error' => 'El producto no está en el carrito.'
                ], 404);
            }
            return back()->with('error', 'El producto no está en el carrito.');
        }
        $cart[$productId]['quantity'] = $quantity;
        session()->put('cart', $cart);
        if ($request->wantsJson() || $request->ajax()) {
            $count = array_sum(array_map(function($row) {
                return max(1, (int)($row['quantity'] ?? 1));
            }, $cart));
            return response()->json([
                'success' => true,
                'message' => 'Cantidad actualizada',
                'count' => $count
            ]);
        }
        return back()->with('success', 'Cantidad actualizada exitosamente.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:productos,Id_Producto']
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.'
        ]);
        $cart = session('cart', []);
        $productId = (int) $validated['product_id'];
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            if ($request->wantsJson() || $request->ajax()) {
                $count = array_sum(array_map(function($row) {
                    return max(1, (int)($row['quantity'] ?? 1));
                }, $cart));
                return response()->json([
                    'success' => true,
                    'message' => 'Producto eliminado del carrito',
                    'count' => $count
                ]);
            }
            return back()->with('success', 'Producto eliminado del carrito.');
        }
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => false,
                'error' => 'Producto no encontrado en el carrito'
            ], 404);
        }
        return back()->with('error', 'Producto no encontrado en el carrito.');
    }

    public function clear()
    {
        session()->forget('cart');
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Carrito vaciado']);
        }
        return back()->with('success', 'Carrito vaciado exitosamente.');
    }

    public function getCount()
    {
        // Obtener el carrito de la sesión (funciona tanto para usuarios autenticados como no autenticados)
        $cart = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        
        return response()->json(['count' => $count]);
    }
}


