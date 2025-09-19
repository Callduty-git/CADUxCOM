<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wishlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id', 'Id_Producto');
    }

    /**
     * Verificar si un producto está en la wishlist del usuario
     */
    public static function isInWishlist(int $userId, int $productId): bool
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->exists();
    }

    /**
     * Agregar producto a la wishlist
     */
    public static function addToWishlist(int $userId, int $productId): bool
    {
        if (self::isInWishlist($userId, $productId)) {
            return false; // Ya está en la wishlist
        }

        return self::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]) !== null;
    }

    /**
     * Remover producto de la wishlist
     */
    public static function removeFromWishlist(int $userId, int $productId): bool
    {
        return self::where('user_id', $userId)
                   ->where('product_id', $productId)
                   ->delete() > 0;
    }

    /**
     * Obtener wishlist del usuario con relaciones
     */
    public static function getUserWishlist(int $userId)
    {
        return self::with(['product.empresa', 'product.subcategoria'])
                   ->where('user_id', $userId)
                   ->orderBy('created_at', 'desc')
                   ->get();
    }

    /**
     * Contar elementos en la wishlist del usuario
     */
    public static function getWishlistCount(int $userId): int
    {
        return self::where('user_id', $userId)->count();
    }

    /**
     * Limpiar toda la wishlist del usuario
     */
    public static function clearUserWishlist(int $userId): bool
    {
        try {
            $deletedCount = self::where('user_id', $userId)->delete();
            \Log::info("Wishlist cleared for user {$userId}, deleted {$deletedCount} items");
            return true;
        } catch (\Exception $e) {
            \Log::error("Error clearing wishlist for user {$userId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener productos de la wishlist con paginación
     */
    public static function getUserWishlistPaginated(int $userId, int $perPage = 12)
    {
        return self::with(['product.empresa', 'product.subcategoria'])
                   ->where('user_id', $userId)
                   ->orderBy('created_at', 'desc')
                   ->paginate($perPage);
    }
}