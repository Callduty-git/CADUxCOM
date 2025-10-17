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
                unset($cart[$pid]); // Eliminar productos inexistentes del carrito
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

        // Cálculos de totales (IVA - el envío es responsabilidad de cada empresa)
        $tax = $subtotal * 0.19; // IVA 19%
        $shipping = 0; // CADUxCOM no maneja envíos directamente
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
        $quantity = max(1, (int)($validated['quantity'] ?? 1));

        $product = Producto::find($productId);
        if (!$product) {
            return $this->responseError($request, 'Producto no encontrado.', 404);
        }

        if ($product->Cantidad <= 0) {
            return $this->responseError($request, 'Este producto está agotado.', 400);
        }

        if ($product->Cantidad < $quantity) {
            return $this->responseError($request, 'No puedes agregar más de la cantidad disponible. Solo quedan ' . $product->Cantidad . ' unidades.', 400);
        }

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $newQuantity = $cart[$productId]['quantity'] + $quantity;
            if ($newQuantity > $product->Cantidad) {
                return $this->responseError($request, 'No puedes agregar más de la cantidad disponible. Stock disponible: ' . $product->Cantidad . ' unidades.', 400);
            }
            $cart[$productId]['quantity'] = $newQuantity;
        } else {
            $cart[$productId] = [
                'quantity' => $quantity,
                'added_at' => now()->toDateTimeString()
            ];
        }

        session()->put('cart', $cart);
        return $this->responseSuccess($request, 'Producto agregado al carrito exitosamente.', $cart);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:productos,Id_Producto'],
            'quantity' => ['required', 'integer', 'min:1']
        ]);

        $productId = (int) $validated['product_id'];
        $quantity = max(1, (int)$validated['quantity']);

        $product = Producto::find($productId);
        if (!$product) {
            return $this->responseError($request, 'Producto no encontrado.', 404);
        }

        if ($product->Cantidad < $quantity) {
            return $this->responseError($request, 'Solo quedan ' . $product->Cantidad . ' unidades disponibles.', 400);
        }

        $cart = session('cart', []);
        if (!isset($cart[$productId])) {
            return $this->responseError($request, 'El producto no está en el carrito.', 404);
        }

        $cart[$productId]['quantity'] = $quantity;
        session()->put('cart', $cart);

        return $this->responseSuccess($request, 'Cantidad actualizada exitosamente.', $cart);
    }

    public function remove(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:productos,Id_Producto']
        ]);

        $productId = (int) $validated['product_id'];
        $cart = session('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            return $this->responseSuccess($request, 'Producto eliminado del carrito.', $cart);
        }

        return $this->responseError($request, 'Producto no encontrado en el carrito.', 404);
    }

    public function clear()
    {
        session()->forget('cart');
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Carrito vaciado.']);
        }
        return back()->with('success', 'Carrito vaciado exitosamente.');
    }

    public function getCount()
    {
        $cart = session('cart', []);
        $count = array_sum(array_column($cart, 'quantity'));
        return response()->json(['count' => $count]);
    }

    /**
     * Métodos auxiliares para respuesta JSON o normal
     */
    private function responseError(Request $request, string $message, int $status)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => false, 'error' => $message], $status);
        }
        return back()->with('error', $message);
    }

    private function responseSuccess(Request $request, string $message, array $cart)
    {
        if ($request->wantsJson() || $request->ajax()) {
            $count = array_sum(array_map(fn($row) => max(1, (int)($row['quantity'] ?? 1)), $cart));
            return response()->json([
                'success' => true,
                'count' => $count,
                'message' => $message
            ]);
        }
        return back()->with('success', $message);
    }
}
