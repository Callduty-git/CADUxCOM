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

    // Relaciones
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'Id_Empresa', 'Id_Empresa');
    }

    public function subcategoria(): BelongsTo
    {
        return $this->belongsTo(Subcategoria::class, 'Id_Subcategoria', 'Id_Subcategoria');
    }

    public function discountRules(): HasMany
    {
        return $this->hasMany(DiscountRule::class, 'empresa_id', 'Id_Empresa');
    }

    // Métodos de descuento
    public function getDiscountedPrice(): array
    {
        return DiscountRule::getBestDiscount($this);
    }

    public function getFinalPrice(): float
    {
        return $this->getDiscountedPrice()['discounted_price'];
    }

    public function getDiscountAmount(): float
    {
        return $this->getDiscountedPrice()['discount_amount'];
    }

    public function getDiscountPercentage(): float
    {
        return $this->getDiscountedPrice()['discount_percentage'];
    }

    public function hasDiscount(): bool
    {
        if ($this->PrecioOriginal > $this->Precio) return true;
        return $this->getDiscountAmount() > 0;
    }

    // Métodos de caducidad
    public function getDaysUntilExpiry(): int
    {
        if (!$this->Fecha_Caducidad) return 999;
        return Carbon::parse($this->Fecha_Caducidad)->diffInDays(now());
    }

    public function isNearExpiry(int $days = 7): bool
    {
        return $this->getDaysUntilExpiry() <= $days;
    }

    public function isExpired(): bool
    {
        if (!$this->Fecha_Caducidad) return false;
        return Carbon::parse($this->Fecha_Caducidad)->isPast();
    }

    public function getExpiryStatus(): string
    {
        if ($this->isExpired()) return 'expired';
        $days = $this->getDaysUntilExpiry();
        if ($days <= 1) return 'critical';
        if ($days <= 3) return 'urgent';
        if ($days <= 7) return 'near_expiry';
        return 'fresh';
    }

    public function getExpiryLabel(): string
    {
        switch ($this->getExpiryStatus()) {
            case 'expired': return 'Caducado';
            case 'critical': return 'Caduca hoy';
            case 'urgent': return 'Caduca pronto';
            case 'near_expiry': return 'Próximo a caducar';
            default: return 'Fresco';
        }
    }

    public function getExpiryClass(): string
    {
        switch ($this->getExpiryStatus()) {
            case 'expired': return 'expired';
            case 'critical': return 'critical';
            case 'urgent': return 'urgent';
            case 'near_expiry': return 'near-expiry';
            default: return 'fresh';
        }
    }

    // Scopes
    public function scopeNearExpiry($query, int $days = 7)
    {
        return $query->where('Fecha_Caducidad', '<=', now()->addDays($days))
                     ->where('Fecha_Caducidad', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('Fecha_Caducidad', '<', now());
    }

    public function scopeWithDiscount($query)
    {
        return $query->whereHas('empresa.discountRules', function ($q) {
            $q->active()->valid();
        });
    }

    // Información de descuento
    public function getDiscountInfo(): array
    {
        $daysUntilExpiry = $this->getDaysUntilExpiry();

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

    public function getSavingsMessage(): string
    {
        $discount = $this->getDiscountedPrice();
        if ($discount['discount_amount'] <= 0) return '';

        $amount = number_format($discount['discount_amount'], 0, ',', '.');
        $percentage = round($discount['discount_percentage'], 0);

        return "Ahorras \${$amount} ({$percentage}%)";
    }

    /**
     * Relación: Un producto tiene muchos comentarios
     */
    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'producto_id', 'Id_Producto');
    }

    /**
     * Obtener comentarios principales del producto
     */
    public function comentariosPrincipales(): HasMany
    {
        return $this->comentarios()->mainComments()->with(['replies', 'user', 'empresa'])->orderBy('created_at', 'desc');
    }
}
