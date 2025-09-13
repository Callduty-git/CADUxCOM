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
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:productos,Id_Producto'],
            'quantity' => ['nullable', 'integer', 'min:1', 'max:10']
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) ($validated['quantity'] ?? 1);

        // Verificar que el producto existe y está disponible
        $product = Producto::find($productId);
        if (!$product) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            return back()->with('error', 'Producto no encontrado.');
        }

        // Verificar stock disponible
        if ($product->Cantidad < $quantity) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Stock insuficiente'], 400);
            }
            return back()->with('error', 'Stock insuficiente para este producto.');
        }

        $cart = session()->get('cart', []);
        
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            // Verificar que no exceda el stock total
            if ($newQuantity > $product->Cantidad) {
                if ($request->wantsJson()) {
                    return response()->json(['error' => 'No se puede agregar más cantidad. Stock disponible: ' . $product->Cantidad], 400);
                }
                return back()->with('error', 'No se puede agregar más cantidad. Stock disponible: ' . $product->Cantidad);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }

        session()->put('cart', $cart);

        if ($request->wantsJson()) {
            $count = array_sum(array_column($cart, 'quantity'));
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
            'quantity' => ['required', 'integer', 'min:1', 'max:10']
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = (int) $validated['quantity'];

        // Verificar stock disponible
        $product = Producto::find($productId);
        if (!$product) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            return back()->with('error', 'Producto no encontrado.');
        }

        if ($product->Cantidad < $quantity) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Stock insuficiente'], 400);
            }
            return back()->with('error', 'Stock insuficiente para este producto.');
        }

        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Cantidad actualizada']);
        }
        
        return back()->with('success', 'Cantidad actualizada exitosamente.');
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer']
        ]);
        
        $cart = session('cart', []);
        $productId = (int) $validated['product_id'];
        
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Producto eliminado del carrito']);
            }
            return back()->with('success', 'Producto eliminado del carrito.');
        }

        if ($request->wantsJson()) {
            return response()->json(['error' => 'Producto no encontrado en el carrito'], 404);
        }
        return back()->with('error', 'Producto no encontrado en el carrito.');
    }

    public function clear()
    {
        session()->forget('cart');
        
        if (request()->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Carrito vaciado']);
        }
        
        return back()->with('success', 'Carrito vaciado exitosamente.');
    }

    public function getCount()
    {
        $cart = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        
        return response()->json(['count' => $count]);
    }
}


