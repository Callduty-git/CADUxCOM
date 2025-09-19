<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Producto extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id_Producto'; 

    protected $fillable = [
        'Nombre',
        'Marca',
        'Fecha_Caducidad',
        'Cantidad',
        'Foto',
        'Descripcion',
        'Precio',
        'PrecioOriginal',
        'Tipo',
        'Codigo',
        'Id_Empresa',
        'Id_Subcategoria',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'Fecha_Caducidad' => 'datetime',
        'Precio' => 'decimal:2',
        'PrecioOriginal' => 'decimal:2',
        'Cantidad' => 'integer',
    ];

    // Define la relación con la Empresa
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'Id_Empresa', 'Id_Empresa');
    }

    // Define la relación con la Subcategoria
    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategoria::class, 'Id_Subcategoria', 'Id_Subcategoria');
    }

    /**
     * Relación: Un producto puede tener múltiples reglas de descuento aplicables
     */
    public function discountRules(): HasMany
    {
        return $this->hasMany(DiscountRule::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Obtener el precio con descuento aplicado
     */
    public function getDiscountedPrice(): array
    {
        return DiscountRule::getBestDiscount($this);
    }

    /**
     * Obtener el precio final (con descuento si aplica)
     */
    public function getFinalPrice(): float
    {
        $discount = $this->getDiscountedPrice();
        return $discount['discounted_price'];
    }

    /**
     * Obtener el monto del descuento aplicado
     */
    public function getDiscountAmount(): float
    {
        $discount = $this->getDiscountedPrice();
        return $discount['discount_amount'];
    }

    /**
     * Obtener el porcentaje de descuento aplicado
     */
    public function getDiscountPercentage(): float
    {
        $discount = $this->getDiscountedPrice();
        return $discount['discount_percentage'];
    }

    /**
     * Verificar si el producto tiene descuento aplicado
     */
    public function hasDiscount(): bool
    {
        // Verificar descuento directo (PrecioOriginal vs Precio)
        if ($this->PrecioOriginal > $this->Precio) {
            return true;
        }
        
        // Verificar descuento por reglas
        return $this->getDiscountAmount() > 0;
    }

    /**
     * Obtener días hasta la caducidad
     */
    public function getDaysUntilExpiry(): int
    {
        if (!$this->Fecha_Caducidad) {
            return 999; // Producto sin fecha de caducidad
        }

        return Carbon::parse($this->Fecha_Caducidad)->diffInDays(now());
    }

    /**
     * Verificar si el producto está próximo a caducar
     */
    public function isNearExpiry(int $days = 7): bool
    {
        return $this->getDaysUntilExpiry() <= $days;
    }

    /**
     * Verificar si el producto ha caducado
     */
    public function isExpired(): bool
    {
        if (!$this->Fecha_Caducidad) {
            return false;
        }

        return Carbon::parse($this->Fecha_Caducidad)->isPast();
    }

    /**
     * Obtener estado de caducidad del producto
     */
    public function getExpiryStatus(): string
    {
        if ($this->isExpired()) {
            return 'expired';
        }

        $days = $this->getDaysUntilExpiry();
        
        if ($days <= 1) {
            return 'critical';
        } elseif ($days <= 3) {
            return 'urgent';
        } elseif ($days <= 7) {
            return 'near_expiry';
        }

        return 'fresh';
    }

    /**
     * Obtener etiqueta de estado de caducidad
     */
    public function getExpiryLabel(): string
    {
        switch ($this->getExpiryStatus()) {
            case 'expired':
                return 'Caducado';
            case 'critical':
                return 'Caduca hoy';
            case 'urgent':
                return 'Caduca pronto';
            case 'near_expiry':
                return 'Próximo a caducar';
            default:
                return 'Fresco';
        }
    }

    /**
     * Obtener clase CSS para el estado de caducidad
     */
    public function getExpiryClass(): string
    {
        switch ($this->getExpiryStatus()) {
            case 'expired':
                return 'expired';
            case 'critical':
                return 'critical';
            case 'urgent':
                return 'urgent';
            case 'near_expiry':
                return 'near-expiry';
            default:
                return 'fresh';
        }
    }

    /**
     * Scope: Filtrar productos próximos a caducar
     */
    public function scopeNearExpiry($query, int $days = 7)
    {
        return $query->where('Fecha_Caducidad', '<=', now()->addDays($days))
                    ->where('Fecha_Caducidad', '>', now());
    }

    /**
     * Scope: Filtrar productos caducados
     */
    public function scopeExpired($query)
    {
        return $query->where('Fecha_Caducidad', '<', now());
    }

    /**
     * Scope: Filtrar productos con descuento aplicable
     */
    public function scopeWithDiscount($query)
    {
        return $query->whereHas('empresa.discountRules', function ($q) {
            $q->active()->valid();
        });
    }

    /**
     * Obtener información completa de descuento para mostrar en la vista
     */
    public function getDiscountInfo(): array
    {
        $daysUntilExpiry = $this->getDaysUntilExpiry();
        
        // Verificar si hay descuento directo
        if ($this->PrecioOriginal > $this->Precio) {
            $discountAmount = $this->PrecioOriginal - $this->Precio;
            $discountPercentage = ($discountAmount / $this->PrecioOriginal) * 100;
            
            return [
                'original_price' => $this->PrecioOriginal,
                'discounted_price' => $this->Precio,
                'discount_amount' => $discountAmount,
                'discount_percentage' => $discountPercentage,
                'has_discount' => true,
                'applied_rule' => null,
                'days_until_expiry' => $daysUntilExpiry,
                'expiry_status' => $this->getExpiryStatus(),
                'expiry_label' => $this->getExpiryLabel(),
                'expiry_class' => $this->getExpiryClass(),
                'savings_message' => $this->getSavingsMessage(),
            ];
        }
        
        // Usar sistema de reglas de descuento
        $discount = $this->getDiscountedPrice();
        
        return [
            'original_price' => $this->Precio,
            'discounted_price' => $discount['discounted_price'],
            'discount_amount' => $discount['discount_amount'],
            'discount_percentage' => $discount['discount_percentage'],
            'has_discount' => $discount['discount_amount'] > 0,
            'applied_rule' => $discount['applied_rule'],
            'days_until_expiry' => $daysUntilExpiry,
            'expiry_status' => $this->getExpiryStatus(),
            'expiry_label' => $this->getExpiryLabel(),
            'expiry_class' => $this->getExpiryClass(),
            'savings_message' => $this->getSavingsMessage(),
        ];
    }

    /**
     * Obtener mensaje de ahorro
     */
    public function getSavingsMessage(): string
    {
        $discount = $this->getDiscountedPrice();
        
        if ($discount['discount_amount'] <= 0) {
            return '';
        }

        $amount = number_format($discount['discount_amount'], 0, ',', '.');
        $percentage = round($discount['discount_percentage'], 0);
        
        return "Ahorras \${$amount} ({$percentage}%)";
    }
}