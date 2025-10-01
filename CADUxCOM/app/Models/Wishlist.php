<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = 'wishlists';

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'notes',
        'priority',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'priority' => 'integer',
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
     * Scope: Filtrar por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filtrar por sesión
     */
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Scope: Filtrar por usuario o sesión
     */
    public function scopeByUserOrSession($query, $userId = null, $sessionId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        } elseif ($sessionId) {
            return $query->where('session_id', $sessionId);
        }

        return $query->whereNull('id'); // No results
    }

    /**
     * Scope: Ordenar por prioridad
     */
    public function scopeOrderByPriority($query)
    {
        return $query->orderBy('priority', 'asc')->orderBy('created_at', 'desc');
    }

    /**
     * Verificar si el producto aún existe y está disponible
     */
    public function isProductAvailable(): bool
    {
        return $this->product && $this->product->Cantidad > 0;
    }

    /**
     * Obtener el estado del producto en la wishlist
     */
    public function getProductStatusAttribute(): string
    {
        if (!$this->product) return 'Producto no encontrado';
        if ($this->product->Cantidad <= 0) return 'Agotado';
        if ($this->product->Cantidad <= 5) return 'Poco stock';
        return 'Disponible';
    }

    /**
     * Obtener la imagen del producto
     */
    public function getProductImageUrlAttribute(): string
    {
        return $this->product && $this->product->Foto
            ? asset('storage/' . $this->product->Foto)
            : asset('images/default-product.png');
    }

    /**
     * Obtener el precio actual del producto
     */
    public function getCurrentPriceAttribute(): ?float
    {
        return $this->product ? (float) $this->product->Precio : null;
    }

    /**
     * Obtener el precio formateado
     */
    public function getFormattedPriceAttribute(): string
    {
        $price = $this->current_price;
        return $price ? '$' . number_format($price, 0, ',', '.') : 'N/A';
    }

    /**
     * Obtener el precio total (precio * cantidad deseada)
     */
    public function getTotalPriceAttribute(): ?float
    {
        $price = $this->current_price;
        return $price ? $price * $this->quantity : null;
    }

    /**
     * Obtener el precio total formateado
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        $total = $this->total_price;
        return $total ? '$' . number_format($total, 0, ',', '.') : 'N/A';
    }

    /**
     * Verificar si el producto tiene descuento
     */
    public function hasDiscount(): bool
    {
        return $this->product && $this->product->PrecioOriginal > $this->product->Precio;
    }

    /**
     * Obtener el porcentaje de descuento
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->hasDiscount()) return null;
        $original = (float) $this->product->PrecioOriginal;
        $current = (float) $this->product->Precio;
        return round((($original - $current) / $original) * 100, 0);
    }

    /**
     * Agregar a la wishlist (gestiona sesión y usuario)
     */
    public static function addToWishlist(int $productId, ?int $userId = null, ?string $sessionId = null, int $quantity = 1, ?string $notes = null): self
    {
        $existing = self::where('product_id', $productId)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) $query->where('user_id', $userId);
                elseif ($sessionId) $query->where('session_id', $sessionId);
            })
            ->first();

        if ($existing) {
            $existing->update(['quantity' => $quantity, 'notes' => $notes]);
            return $existing;
        }

        return self::create([
            'user_id' => $userId,
            'session_id' => $sessionId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'notes' => $notes,
            'priority' => self::getNextPriority($userId, $sessionId),
        ]);
    }

    /**
     * Obtener la siguiente prioridad disponible
     */
    private static function getNextPriority(?int $userId = null, ?string $sessionId = null): int
    {
        $maxPriority = self::byUserOrSession($userId, $sessionId)->max('priority');
        return ($maxPriority ?? 0) + 1;
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
     * Migrar wishlist de sesión a usuario
     */
    public static function migrateSessionToUser(string $sessionId, int $userId): void
    {
        self::where('session_id', $sessionId)
            ->whereNull('user_id')
            ->update(['user_id' => $userId, 'session_id' => null]);
    }

    /**
     * Obtener estadísticas de la wishlist
     */
    public static function getWishlistStats(?int $userId = null, ?string $sessionId = null): array
    {
        $query = self::byUserOrSession($userId, $sessionId);

        $totalItems = $query->count();
        $availableItems = $query->whereHas('product', fn($q) => $q->where('Cantidad', '>', 0))->count();
        $outOfStockItems = $totalItems - $availableItems;
        $totalValue = $query->whereHas('product')->get()->sum('total_price');

        return [
            'total_items' => $totalItems,
            'available_items' => $availableItems,
            'out_of_stock_items' => $outOfStockItems,
            'total_value' => $totalValue,
            'formatted_total_value' => $totalValue ? '$' . number_format($totalValue, 0, ',', '.') : '$0',
        ];
    }

    /**
     * Limpiar toda la wishlist del usuario
     */
    public static function clearUserWishlist(int $userId): bool
    {
        try {
            $deletedCount = self::where('user_id', $userId)->delete();
            Log::info("Wishlist cleared for user {$userId}, deleted {$deletedCount} items");
            return true;
        } catch (\Exception $e) {
            Log::error("Error clearing wishlist for user {$userId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtener wishlist del usuario con relaciones y paginación
     */
    public static function getUserWishlistPaginated(int $userId, int $perPage = 12)
    {
        return self::with(['product.empresa', 'product.subcategoria'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Obtener wishlist del usuario (sin paginación)
     */
    public static function getUserWishlist(int $userId)
    {
        return self::with(['product.empresa', 'product.subcategoria'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtener contador de wishlist del usuario
     */
    public static function getWishlistCount(int $userId): int
    {
        return self::where('user_id', $userId)->count();
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
}
