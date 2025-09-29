<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class WishlistController extends Controller
{
    /**
     * Constructor - (Eliminado middleware 'auth', protección se realiza en rutas)
     */
    public function __construct()
    {
        // Protección de autenticación se realiza en las rutas web y api
    }

    /**
     * Mostrar la wishlist del usuario
     */
    public function index(Request $request)
    {
        $userId = Auth::id();
        $wishlistItems = Wishlist::getUserWishlist($userId);
        $wishlistCount = Wishlist::getWishlistCount($userId);

        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'wishlist' => $wishlistItems,
                'wishlist_count' => $wishlistCount
            ]);
        }

        return view('wishlist.index', compact('wishlistItems', 'wishlistCount'));
    }

    /**
     * Agregar producto a la wishlist
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Verificar que el producto existe y está activo
        $product = Producto::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $wishlistItem = Wishlist::addToWishlist($productId, $userId);
        $success = $wishlistItem !== null;

        if ($success) {
            $wishlistCount = Wishlist::getWishlistCount($userId);
            return response()->json([
                'success' => true,
                'message' => 'Producto agregado a favoritos',
                'wishlist_count' => $wishlistCount,
                'is_in_wishlist' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'El producto ya está en favoritos'
        ], 400);
    }

    /**
     * Remover producto de la wishlist
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $success = Wishlist::removeFromWishlist($userId, $productId);

        if ($success) {
            $wishlistCount = Wishlist::getWishlistCount($userId);
            return response()->json([
                'success' => true,
                'message' => 'Producto removido de favoritos',
                'wishlist_count' => $wishlistCount,
                'is_in_wishlist' => false
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'El producto no estaba en favoritos'
        ], 400);
    }

    /**
     * Toggle producto en la wishlist (agregar si no está, remover si está)
     */
    public function toggle(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        // Verificar que el producto existe
        $product = Producto::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Producto no encontrado'
            ], 404);
        }

        $isInWishlist = Wishlist::isInWishlist($userId, $productId);

        if ($isInWishlist) {
            // Remover de la wishlist
            $success = Wishlist::removeFromWishlist($userId, $productId);
            $message = 'Producto removido de favoritos';
            $isInWishlist = false;
        } else {
            // Agregar a la wishlist
            $wishlistItem = Wishlist::addToWishlist($productId, $userId);
            $success = $wishlistItem !== null;
            $message = 'Producto agregado a favoritos';
            $isInWishlist = true;
        }

        if ($success) {
            $wishlistCount = Wishlist::getWishlistCount($userId);
            return response()->json([
                'success' => true,
                'message' => $message,
                'wishlist_count' => $wishlistCount,
                'is_in_wishlist' => $isInWishlist
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar favoritos'
        ], 500);
    }

    /**
     * Obtener el estado de un producto en la wishlist
     */
    public function status(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:productos,Id_Producto',
        ], [
            'product_id.required' => 'El ID de producto es obligatorio.',
            'product_id.integer' => 'El ID de producto debe ser un número.',
            'product_id.exists' => 'El producto seleccionado no existe.'
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $isInWishlist = Wishlist::isInWishlist($userId, $productId);

        return response()->json([
            'is_in_wishlist' => $isInWishlist
        ]);
    }

    /**
     * Obtener el contador de la wishlist
     */
    public function count(): JsonResponse
    {
        $userId = Auth::id();
        $count = Wishlist::getWishlistCount($userId);

        return response()->json([
            'count' => $count
        ]);
    }

    /**
     * Limpiar toda la wishlist del usuario
     */
    public function clear(Request $request)
    {
        \Log::info('WishlistController::clear called');

        $userId = Auth::id();
        \Log::info('User ID:', ['user_id' => $userId]);

        if (!$userId) {
            \Log::warning('User not authenticated in clear method');
            return response()->json([
                'success' => false,
                'message' => 'Usuario no autenticado'
            ], 401);
        }

        $countBefore = Wishlist::getWishlistCount($userId);
        \Log::info('Items before clear:', ['count' => $countBefore]);

        $success = Wishlist::clearUserWishlist($userId);

        $countAfter = Wishlist::getWishlistCount($userId);
        \Log::info('Items after clear:', ['count' => $countAfter, 'success' => $success]);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'Lista de favoritos limpiada correctamente',
                'wishlist_count' => 0
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Error al limpiar la lista de favoritos'
        ], 500);
    }

    /**
     * Obtener múltiples estados de productos en la wishlist
     */
    public function getMultipleStatus(Request $request): JsonResponse
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:productos,Id_Producto',
        ], [
            'product_ids.required' => 'Debes enviar un array de IDs de productos.',
            'product_ids.array' => 'El formato de los IDs de productos no es válido.',
            'product_ids.*.integer' => 'Cada ID de producto debe ser un número.',
            'product_ids.*.exists' => 'Algún producto seleccionado no existe.'
        ]);

        $userId = Auth::id();
        $productIds = $request->product_ids;

        $statuses = [];
        foreach ($productIds as $productId) {
            $statuses[$productId] = Wishlist::isInWishlist($userId, $productId);
        }

        return response()->json([
            'statuses' => $statuses
        ]);
    }
}