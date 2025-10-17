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
     * Casts automáticos
     */
    protected $casts = [
        'Fecha_Caducidad' => 'datetime',
        'Precio' => 'decimal:2',
        'PrecioOriginal' => 'decimal:2',
        'Cantidad' => 'integer',
    ];

    /* ============================
     * RELACIONES
     * ============================
     */

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

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'producto_id', 'Id_Producto');
    }

    public function comentariosPrincipales(): HasMany
    {
        return $this->comentarios()
            ->mainComments()
            ->with(['replies', 'user', 'empresa'])
            ->orderBy('created_at', 'desc');
    }

    /* ============================
     * DESCUENTOS
     * ============================
     */

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

    /* ============================
     * CADUCIDAD
     * ============================
     */

    public function getDaysUntilExpiry(): int
    {
        if (!$this->Fecha_Caducidad) return 999;

        $expiryEndOfDay = Carbon::parse($this->Fecha_Caducidad)->endOfDay();
        $now = Carbon::now();

        return $expiryEndOfDay->lessThan($now) ? 0 : $now->diffInDays($expiryEndOfDay);
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

    /* ============================
     * SCOPES
     * ============================
     */

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

    /* ============================
     * INFORMACIÓN COMPLETA DE DESCUENTO
     * ============================
     */

    public function getDiscountInfo(): array
    {
        $daysUntilExpiry = $this->getDaysUntilExpiry();
        $discount = $this->getDiscountedPrice();

        // 1️⃣ Descuento por regla progresiva
        if ($discount['discount_amount'] > 0) {
            return [
                'original_price' => $this->PrecioOriginal > 0 ? $this->PrecioOriginal : $this->Precio,
                'discounted_price' => $discount['discounted_price'],
                'discount_amount' => $discount['discount_amount'],
                'discount_percentage' => $discount['discount_percentage'],
                'has_discount' => true,
                'applied_rule' => $discount['applied_rule'],
                'days_until_expiry' => $daysUntilExpiry,
                'expiry_status' => $this->getExpiryStatus(),
                'expiry_label' => $this->getExpiryLabel(),
                'expiry_class' => $this->getExpiryClass(),
                'savings_message' => $this->getSavingsMessage(),
            ];
        }

        // 2️⃣ Descuento manual (PrecioOriginal > Precio)
        if ($this->PrecioOriginal > $this->Precio) {
            $discountAmount = $this->PrecioOriginal - $this->Precio;
            $discountPercentage = round(($discountAmount / $this->PrecioOriginal) * 100, 0);

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

        // 3️⃣ Sin descuento
        return [
            'original_price' => $this->Precio,
            'discounted_price' => $discount['discounted_price'],
            'discount_amount' => $discount['discount_amount'],
            'discount_percentage' => $discount['discount_percentage'],
            'has_discount' => false,
            'applied_rule' => null,
            'days_until_expiry' => $daysUntilExpiry,
            'expiry_status' => $this->getExpiryStatus(),
            'expiry_label' => $this->getExpiryLabel(),
            'expiry_class' => $this->getExpiryClass(),
            'savings_message' => '',
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
}
