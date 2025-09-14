<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

/**
 * Modelo Coupon - Representa un cupón de descuento en el sistema
 * 
 * Este modelo maneja los cupones de descuento, promociones y ofertas especiales
 * que pueden ser aplicados a las órdenes de compra.
 */
class Coupon extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'coupons';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'minimum_amount',
        'maximum_discount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'starts_at',
        'expires_at',
        'is_active',
        'applicable_categories',
        'applicable_products',
        'excluded_products',
        'terms_conditions',
        'created_by',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'value' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
        'excluded_products' => 'array',
    ];

    /**
     * Tipos de cupones válidos
     */
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED_AMOUNT = 'fixed_amount';
    const TYPE_FREE_SHIPPING = 'free_shipping';

    /**
     * Relación: Un cupón puede ser usado en muchas órdenes
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'coupon_code', 'code');
    }

    /**
     * Scope: Filtrar cupones activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filtrar cupones válidos (activos y dentro del período de validez)
     */
    public function scopeValid($query)
    {
        $now = now();
        return $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('starts_at')
                          ->orWhere('starts_at', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>=', $now);
                    });
    }

    /**
     * Scope: Filtrar cupones por código
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    /**
     * Verificar si el cupón es válido
     */
    public function isValid(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // Verificar fechas de validez
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }

        if ($this->expires_at && $this->expires_at < $now) {
            return false;
        }

        // Verificar límite de uso
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si el cupón puede ser aplicado a un monto específico
     */
    public function canBeAppliedToAmount(float $amount): bool
    {
        return $amount >= $this->minimum_amount;
    }

    /**
     * Calcular el descuento para un monto específico
     */
    public function calculateDiscount(float $amount): float
    {
        if (!$this->isValid() || !$this->canBeAppliedToAmount($amount)) {
            return 0;
        }

        $discount = 0;

        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                $discount = ($amount * $this->value) / 100;
                if ($this->maximum_discount) {
                    $discount = min($discount, $this->maximum_discount);
                }
                break;

            case self::TYPE_FIXED_AMOUNT:
                $discount = min($this->value, $amount);
                break;

            case self::TYPE_FREE_SHIPPING:
                // Para envío gratuito, el descuento se maneja en el checkout
                $discount = 0;
                break;
        }

        return round($discount, 2);
    }

    /**
     * Verificar si el cupón es aplicable a un producto específico
     */
    public function isApplicableToProduct(int $productId): bool
    {
        // Si hay productos excluidos, verificar que no esté en la lista
        if ($this->excluded_products && in_array($productId, $this->excluded_products)) {
            return false;
        }

        // Si hay productos específicos aplicables, verificar que esté en la lista
        if ($this->applicable_products && !in_array($productId, $this->applicable_products)) {
            return false;
        }

        return true;
    }

    /**
     * Verificar si el cupón es aplicable a una categoría específica
     */
    public function isApplicableToCategory(int $categoryId): bool
    {
        // Si hay categorías específicas aplicables, verificar que esté en la lista
        if ($this->applicable_categories && !in_array($categoryId, $this->applicable_categories)) {
            return false;
        }

        return true;
    }

    /**
     * Incrementar el contador de uso
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }

    /**
     * Obtener el tipo de cupón en español
     */
    public function getTypeInSpanish(): string
    {
        $types = [
            self::TYPE_PERCENTAGE => 'Porcentaje',
            self::TYPE_FIXED_AMOUNT => 'Cantidad Fija',
            self::TYPE_FREE_SHIPPING => 'Envío Gratuito',
        ];

        return $types[$this->type] ?? 'Desconocido';
    }

    /**
     * Obtener el valor del cupón formateado
     */
    public function getFormattedValueAttribute(): string
    {
        switch ($this->type) {
            case self::TYPE_PERCENTAGE:
                return $this->value . '%';
            case self::TYPE_FIXED_AMOUNT:
                return '$' . number_format($this->value, 0, ',', '.');
            case self::TYPE_FREE_SHIPPING:
                return 'Envío Gratuito';
            default:
                return 'N/A';
        }
    }

    /**
     * Obtener el estado del cupón
     */
    public function getStatusAttribute(): string
    {
        if (!$this->is_active) {
            return 'Inactivo';
        }

        if (!$this->isValid()) {
            return 'Expirado';
        }

        return 'Activo';
    }

    /**
     * Obtener días restantes hasta la expiración
     */
    public function getDaysUntilExpirationAttribute(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Boot method para normalizar el código del cupón
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($coupon) {
            // Convertir el código a mayúsculas
            $coupon->code = strtoupper($coupon->code);
        });
    }
}