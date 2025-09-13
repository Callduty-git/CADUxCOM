<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/**
 * Controlador WishlistController - Maneja la lista de deseos de los usuarios
 * 
 * Este controlador permite a los usuarios agregar, eliminar y gestionar
 * productos en su lista de deseos, tanto para usuarios registrados como invitados.
 */
class WishlistController extends Controller
{
    /**
     * Constructor - Aplicar middleware de autenticación opcional
     */
    public function __construct()
    {
        // No requerimos autenticación obligatoria ya que también funciona para invitados
    }

    /**
     * Mostrar la lista de deseos del usuario
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        // Obtener items de la wishlist
        $wishlistItems = Wishlist::with(['product.empresa', 'product.subcategoria'])
            ->byUserOrSession($userId, $sessionId)
            ->orderByPriority()
            ->get();

        // Obtener estadísticas
        $stats = Wishlist::getWishlistStats($userId, $sessionId);

        return view('wishlist.index', compact('wishlistItems', 'stats'));
    }

    /**
     * Agregar un producto a la lista de deseos
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
            'quantity' => 'nullable|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity ?? 1;
        $notes = $request->notes;

        // Verificar que el producto existe y está disponible
        $product = Producto::find($productId);
        if (!$product) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
            return back()->with('error', 'Producto no encontrado.');
        }

        $userId = Auth::id();
        $sessionId = Session::getId();

        // Agregar a la wishlist
        $wishlistItem = Wishlist::addToWishlist($productId, $userId, $sessionId, $quantity, $notes);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado a tu lista de deseos',
                'wishlist_count' => Wishlist::byUserOrSession($userId, $sessionId)->count(),
            ]);
        }

        return back()->with('success', 'Producto agregado a tu lista de deseos.');
    }

    /**
     * Eliminar un producto de la lista de deseos
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ]);

        $productId = $request->product_id;
        $userId = Auth::id();
        $sessionId = Session::getId();

        $wishlistItem = Wishlist::byUserOrSession($userId, $sessionId)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlistItem) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado en tu lista de deseos'], 404);
            }
            return back()->with('error', 'Producto no encontrado en tu lista de deseos.');
        }

        $wishlistItem->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Producto eliminado de tu lista de deseos',
                'wishlist_count' => Wishlist::byUserOrSession($userId, $sessionId)->count(),
            ]);
        }

        return back()->with('success', 'Producto eliminado de tu lista de deseos.');
    }

    /**
     * Actualizar la cantidad de un producto en la wishlist
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
            'quantity' => 'required|integer|min:1|max:10',
            'notes' => 'nullable|string|max:500',
        ]);

        $productId = $request->product_id;
        $quantity = $request->quantity;
        $notes = $request->notes;
        $userId = Auth::id();
        $sessionId = Session::getId();

        $wishlistItem = Wishlist::byUserOrSession($userId, $sessionId)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlistItem) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado en tu lista de deseos'], 404);
            }
            return back()->with('error', 'Producto no encontrado en tu lista de deseos.');
        }

        $wishlistItem->update([
            'quantity' => $quantity,
            'notes' => $notes,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Lista de deseos actualizada',
            ]);
        }

        return back()->with('success', 'Lista de deseos actualizada.');
    }

    /**
     * Mover un producto a una nueva prioridad
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function move(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
            'priority' => 'required|integer|min:1',
        ]);

        $productId = $request->product_id;
        $priority = $request->priority;
        $userId = Auth::id();
        $sessionId = Session::getId();

        $wishlistItem = Wishlist::byUserOrSession($userId, $sessionId)
            ->where('product_id', $productId)
            ->first();

        if (!$wishlistItem) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Producto no encontrado en tu lista de deseos'], 404);
            }
            return back()->with('error', 'Producto no encontrado en tu lista de deseos.');
        }

        $wishlistItem->moveToPriority($priority);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Prioridad actualizada',
            ]);
        }

        return back()->with('success', 'Prioridad actualizada.');
    }

    /**
     * Agregar todos los productos de la wishlist al carrito
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function addAllToCart(Request $request)
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $wishlistItems = Wishlist::with('product')
            ->byUserOrSession($userId, $sessionId)
            ->whereHas('product', function ($query) {
                $query->where('Cantidad', '>', 0);
            })
            ->get();

        if ($wishlistItems->isEmpty()) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'No hay productos disponibles en tu lista de deseos'], 400);
            }
            return back()->with('error', 'No hay productos disponibles en tu lista de deseos.');
        }

        $cart = Session::get('cart', []);
        $addedCount = 0;
        $errors = [];

        foreach ($wishlistItems as $item) {
            $product = $item->product;
            $productId = $product->Id_Producto;
            $quantity = $item->quantity;

            // Verificar stock disponible
            if ($product->Cantidad < $quantity) {
                $errors[] = "Solo hay {$product->Cantidad} unidades disponibles de {$product->Nombre}";
                continue;
            }

            // Agregar al carrito
            if (isset($cart[$productId])) {
                $newQuantity = $cart[$productId]['quantity'] + $quantity;
                if ($newQuantity > $product->Cantidad) {
                    $errors[] = "No se puede agregar más cantidad de {$product->Nombre}. Stock disponible: {$product->Cantidad}";
                    continue;
                }
                $cart[$productId]['quantity'] = $newQuantity;
            } else {
                $cart[$productId] = [
                    'quantity' => $quantity,
                    'added_at' => now()->toDateTimeString()
                ];
            }

            $addedCount++;
        }

        Session::put('cart', $cart);

        if ($request->wantsJson()) {
            $response = [
                'success' => true,
                'message' => "Se agregaron {$addedCount} productos al carrito",
                'added_count' => $addedCount,
            ];

            if (!empty($errors)) {
                $response['errors'] = $errors;
            }

            return response()->json($response);
        }

        $message = "Se agregaron {$addedCount} productos al carrito.";
        if (!empty($errors)) {
            $message .= " Algunos productos no pudieron ser agregados: " . implode(', ', $errors);
        }

        return back()->with('success', $message);
    }

    /**
     * Vaciar toda la lista de deseos
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function clear(Request $request)
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $deletedCount = Wishlist::byUserOrSession($userId, $sessionId)->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Se eliminaron {$deletedCount} productos de tu lista de deseos",
                'deleted_count' => $deletedCount,
            ]);
        }

        return back()->with('success', "Se eliminaron {$deletedCount} productos de tu lista de deseos.");
    }

    /**
     * Obtener el conteo de productos en la wishlist
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCount()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $count = Wishlist::byUserOrSession($userId, $sessionId)->count();

        return response()->json(['count' => $count]);
    }

    /**
     * Migrar wishlist de sesión a usuario (cuando el usuario se registra/inicia sesión)
     * 
     * @return void
     */
    public function migrateSessionToUser()
    {
        if (Auth::check()) {
            $sessionId = Session::getId();
            $userId = Auth::id();

            Wishlist::migrateSessionToUser($sessionId, $userId);
        }
    }
}