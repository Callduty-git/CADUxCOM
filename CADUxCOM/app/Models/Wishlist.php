<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Wishlist - Representa un item en la lista de deseos de un usuario
 * 
 * Este modelo permite a los usuarios guardar productos para comprar después,
 * tanto para usuarios registrados como para invitados con sesión.
 */
class Wishlist extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'wishlists';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'notes',
        'priority',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'quantity' => 'integer',
        'priority' => 'integer',
    ];

    /**
     * Relación: Un item de wishlist pertenece a un usuario (opcional)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un item de wishlist pertenece a un producto
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
        if (!$this->product) {
            return 'Producto no encontrado';
        }

        if ($this->product->Cantidad <= 0) {
            return 'Agotado';
        }

        if ($this->product->Cantidad <= 5) {
            return 'Poco stock';
        }

        return 'Disponible';
    }

    /**
     * Obtener la imagen del producto
     */
    public function getProductImageUrlAttribute(): string
    {
        if ($this->product && $this->product->Foto) {
            return asset('storage/' . $this->product->Foto);
        }
        
        return asset('images/default-product.png');
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
        if (!$this->hasDiscount()) {
            return null;
        }

        $original = (float) $this->product->PrecioOriginal;
        $current = (float) $this->product->Precio;
        
        return round((($original - $current) / $original) * 100, 0);
    }

    /**
     * Mover item a una nueva prioridad
     */
    public function moveToPriority(int $newPriority): void
    {
        $this->update(['priority' => $newPriority]);
    }

    /**
     * Agregar a la lista de deseos
     */
    public static function addToWishlist(int $productId, int $userId = null, string $sessionId = null, int $quantity = 1, string $notes = null): self
    {
        // Verificar si ya existe
        $existing = self::where('product_id', $productId)
            ->where(function ($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } elseif ($sessionId) {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($existing) {
            // Actualizar cantidad y notas si ya existe
            $existing->update([
                'quantity' => $quantity,
                'notes' => $notes,
            ]);
            return $existing;
        }

        // Crear nuevo item
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
    private static function getNextPriority(int $userId = null, string $sessionId = null): int
    {
        $maxPriority = self::byUserOrSession($userId, $sessionId)->max('priority');
        return ($maxPriority ?? 0) + 1;
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
    public static function getWishlistStats(int $userId = null, string $sessionId = null): array
    {
        $query = self::byUserOrSession($userId, $sessionId);
        
        $totalItems = $query->count();
        $availableItems = $query->whereHas('product', function ($q) {
            $q->where('Cantidad', '>', 0);
        })->count();
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
}