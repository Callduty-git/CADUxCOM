<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * Modelo DiscountRule - Representa reglas de descuentos progresivos
 * 
 * Este modelo maneja las reglas de descuento automático basadas en
 * la proximidad a la fecha de caducidad de los productos.
 */
class DiscountRule extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'discount_rules';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'empresa_id',
        'name',
        'description',
        'days_before_expiry',
        'discount_type',
        'discount_value',
        'minimum_discount',
        'maximum_discount',
        'minimum_product_price',
        'applicable_categories',
        'applicable_products',
        'excluded_products',
        'is_active',
        'is_automatic',
        'starts_at',
        'expires_at',
        'usage_count',
        'total_savings',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'discount_value' => 'decimal:2',
        'minimum_discount' => 'decimal:2',
        'maximum_discount' => 'decimal:2',
        'minimum_product_price' => 'decimal:2',
        'applicable_categories' => 'array',
        'applicable_products' => 'array',
        'excluded_products' => 'array',
        'is_active' => 'boolean',
        'is_automatic' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'usage_count' => 'integer',
        'total_savings' => 'decimal:2',
    ];

    /**
     * Tipos de descuento válidos
     */
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED_AMOUNT = 'fixed_amount';

    /**
     * Relación: Una regla de descuento pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Scope: Filtrar reglas activas
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filtrar reglas válidas (activas y dentro del período de validez)
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
     * Scope: Filtrar reglas por empresa
     */
    public function scopeByEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Scope: Filtrar reglas por días antes de caducidad
     */
    public function scopeByDaysBeforeExpiry($query, $days)
    {
        return $query->where('days_before_expiry', '>=', $days);
    }

    /**
     * Verificar si la regla es válida
     */
    public function isValid(): bool
    {
        if (!$this->is_active) return false;

        $now = now();
        if ($this->starts_at && $this->starts_at > $now) return false;
        if ($this->expires_at && $this->expires_at < $now) return false;

        return true;
    }

    /**
     * Verificar si la regla es aplicable a un producto específico
     */
    public function isApplicableToProduct(Producto $producto): bool
    {
        if ($producto->Id_Empresa !== $this->empresa_id) return false;
        if ($producto->Precio < $this->minimum_product_price) return false;
        if ($this->excluded_products && in_array($producto->Id_Producto, $this->excluded_products)) return false;
        if ($this->applicable_products && !in_array($producto->Id_Producto, $this->applicable_products)) return false;
        if ($this->applicable_categories && !in_array($producto->Id_Subcategoria, $this->applicable_categories)) return false;

        return true;
    }

    /**
     * Calcular el descuento para un producto específico
     */
    public function calculateDiscount(Producto $producto): array
    {
        // No aplicar reglas si el producto ya está vencido
        if (method_exists($producto, 'isExpired') && $producto->isExpired()) {
            return [
                'discount_amount' => 0,
                'discounted_price' => $producto->Precio,
                'discount_percentage' => 0,
                'applied_rule' => null,
            ];
        }

        if (!$this->isValid() || !$this->isApplicableToProduct($producto)) {
            return [
                'discount_amount' => 0,
                'discounted_price' => $producto->Precio,
                'discount_percentage' => 0,
                'applied_rule' => null,
            ];
        }

        // Verificar si faltan más días que el umbral configurado
        $daysUntilExpiry = method_exists($producto, 'getDaysUntilExpiry')
            ? $producto->getDaysUntilExpiry()
            : ($producto->Fecha_Caducidad ? Carbon::parse($producto->Fecha_Caducidad)->diffInDays(now()) : 999);

        if ($daysUntilExpiry > (int) $this->days_before_expiry) {
            return [
                'discount_amount' => 0,
                'discounted_price' => $producto->Precio,
                'discount_percentage' => 0,
                'applied_rule' => null,
            ];
        }

        // Base del descuento: precio original si existe, o precio actual
        $originalPrice = $producto->PrecioOriginal > 0 ? $producto->PrecioOriginal : $producto->Precio;
        $discountAmount = 0;

        switch ($this->discount_type) {
            case self::TYPE_PERCENTAGE:
                $discountAmount = ($originalPrice * $this->discount_value) / 100;
                break;
            case self::TYPE_FIXED_AMOUNT:
                $discountAmount = min($this->discount_value, $originalPrice);
                break;
        }

        if ($this->minimum_discount && $discountAmount < $this->minimum_discount) {
            $discountAmount = $this->minimum_discount;
        }

        if ($this->maximum_discount && $discountAmount > $this->maximum_discount) {
            $discountAmount = $this->maximum_discount;
        }

        $discountAmount = min($discountAmount, $originalPrice);

        $discountedPrice = $originalPrice - $discountAmount;
        $discountPercentage = $originalPrice > 0 ? ($discountAmount / $originalPrice) * 100 : 0;

        return [
            'discount_amount' => round($discountAmount, 2),
            'discounted_price' => round($discountedPrice, 2),
            'discount_percentage' => round($discountPercentage, 0),
            'applied_rule' => $this,
        ];
    }

    /**
     * Obtener reglas aplicables para un producto
     */
    public static function getApplicableRules(Producto $producto)
    {
        $daysUntilExpiry = $producto->Fecha_Caducidad
            ? Carbon::parse($producto->Fecha_Caducidad)->diffInDays(now())
            : 999;

        return self::valid()
            ->byEmpresa($producto->Id_Empresa)
            ->byDaysBeforeExpiry($daysUntilExpiry)
            ->orderBy('days_before_expiry', 'desc')
            ->get()
            ->filter(fn($rule) => $rule->isApplicableToProduct($producto));
    }

    /**
     * Obtener el mejor descuento para un producto
     */
    public static function getBestDiscount(Producto $producto): array
    {
        $applicableRules = self::getApplicableRules($producto);

        if ($applicableRules->isEmpty()) {
            return [
                'discount_amount' => 0,
                'discounted_price' => $producto->Precio,
                'discount_percentage' => 0,
                'applied_rule' => null,
            ];
        }

        $bestDiscount = null;
        $maxDiscountAmount = 0;

        foreach ($applicableRules as $rule) {
            $discount = $rule->calculateDiscount($producto);
            if ($discount['discount_amount'] > $maxDiscountAmount) {
                $maxDiscountAmount = $discount['discount_amount'];
                $bestDiscount = $discount;
            }
        }

        return $bestDiscount ?: [
            'discount_amount' => 0,
            'discounted_price' => $producto->Precio,
            'discount_percentage' => 0,
            'applied_rule' => null,
        ];
    }

    /**
     * Incrementar contador de uso
     */
    public function incrementUsage(float $savings = 0): void
    {
        $this->increment('usage_count');
        $this->increment('total_savings', $savings);
    }

    /**
     * Obtener estadísticas de la regla
     */
    public function getStats(): array
    {
        return [
            'usage_count' => $this->usage_count,
            'total_savings' => $this->total_savings,
            'average_savings_per_use' => $this->usage_count > 0 
                ? round($this->total_savings / $this->usage_count, 2)
                : 0,
        ];
    }

    /**
     * Crear reglas por defecto (3) para una empresa
     */
    public static function createDefaultRules(int $empresaId): void
    {
        $defaultRules = [
            ['name' => 'Descuento 7 días', 'description' => 'Descuento del 10% para productos que caducan en 7 días', 'days_before_expiry' => 7, 'discount_type' => self::TYPE_PERCENTAGE, 'discount_value' => 10, 'minimum_product_price' => 1000],
            ['name' => 'Descuento 3 días', 'description' => 'Descuento del 20% para productos que caducan en 3 días', 'days_before_expiry' => 3, 'discount_type' => self::TYPE_PERCENTAGE, 'discount_value' => 20, 'minimum_product_price' => 1000],
            ['name' => 'Descuento 1 día', 'description' => 'Descuento del 30% para productos que caducan en 1 día', 'days_before_expiry' => 1, 'discount_type' => self::TYPE_PERCENTAGE, 'discount_value' => 30, 'minimum_product_price' => 1000],
        ];

        $currentCount = self::byEmpresa($empresaId)->count();
        $needed = max(0, 3 - $currentCount);

        for ($i = 0; $i < $needed; $i++) {
            $ruleData = $defaultRules[$i];
            $ruleData['empresa_id'] = $empresaId;
            self::create($ruleData);
        }
    }

    /**
     * Crear reglas adicionales hasta completar 5
     */
    public static function createAdditionalRulesUpToFive(int $empresaId): void
    {
        $additionalRules = [
            ['name' => 'Descuento 14 días', 'description' => 'Descuento del 5% para productos que caducan en 14 días', 'days_before_expiry' => 14, 'discount_type' => self::TYPE_PERCENTAGE, 'discount_value' => 5, 'minimum_product_price' => 1000],
            ['name' => 'Descuento 5 días', 'description' => 'Descuento del 15% para productos que caducan en 5 días', 'days_before_expiry' => 5, 'discount_type' => self::TYPE_PERCENTAGE, 'discount_value' => 15, 'minimum_product_price' => 1000],
        ];

        $limit = (int) config('discount.rules_limit', 5);
        $currentCount = self::byEmpresa($empresaId)->count();
        if ($currentCount >= $limit) return;

        $needed = min(max(0, $limit - $currentCount), count($additionalRules));
        for ($i = 0; $i < $needed; $i++) {
            $ruleData = $additionalRules[$i];
            $ruleData['empresa_id'] = $empresaId;
            self::create($ruleData);
        }
    }
}
