<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modelo Order - Representa una orden de compra en el sistema.
 * 
 * Este modelo maneja toda la información relacionada con las órdenes de compra,
 * incluyendo datos del cliente, envío, facturación, totales, cupones,
 * estado, puntos de fidelidad y métodos de pago.
 */
class Order extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'orders';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'order_number',
        'user_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_postal_code',
        'shipping_country',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_postal_code',
        'billing_country',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'coupon_code',
        'coupon_discount',
        'status',
        'payment_method',
        'payment_reference',
        'paid_at',
        'tracking_number',
        'shipped_at',
        'delivered_at',
        'notes',
        'admin_notes',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    /**
     * Estados válidos para una orden
     */
    const STATUS_PENDING = 'pending';
    const STATUS_PAID = 'paid';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_REFUNDED = 'refunded';

    /**
     * Métodos de pago válidos
     */
    const PAYMENT_CREDIT_CARD = 'credit_card';
    const PAYMENT_DEBIT_CARD = 'debit_card';
    const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    const PAYMENT_DIGITAL_WALLET = 'digital_wallet';

    /**
     * Relación: Una orden pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una orden tiene muchos ítems
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Relación: Una orden puede tener un cupón aplicado
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class, 'coupon_code', 'code');
    }

    /**
     * Relación: Una orden tiene muchos puntos de fidelidad
     */
    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    /**
     * Scope: Filtrar órdenes por estado
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filtrar órdenes por usuario
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filtrar órdenes por rango de fechas
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Generar número de orden único
     */
    public static function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    /**
     * Verificar si la orden puede ser cancelada
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PAID]);
    }

    /**
     * Verificar si la orden puede ser reembolsada
     */
    public function canBeRefunded(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_PROCESSING, self::STATUS_SHIPPED]);
    }

    /**
     * Obtener el estado de la orden en español
     */
    public function getStatusInSpanish(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_PAID => 'Pagada',
            self::STATUS_PROCESSING => 'En Procesamiento',
            self::STATUS_SHIPPED => 'Enviada',
            self::STATUS_DELIVERED => 'Entregada',
            self::STATUS_CANCELLED => 'Cancelada',
            self::STATUS_REFUNDED => 'Reembolsada',
        ];

        return $statuses[$this->status] ?? 'Desconocido';
    }

    /**
     * Obtener el método de pago en español
     */
    public function getPaymentMethodInSpanish(): string
    {
        $methods = [
            self::PAYMENT_CREDIT_CARD => 'Tarjeta de Crédito',
            self::PAYMENT_DEBIT_CARD => 'Tarjeta Débito',
            self::PAYMENT_BANK_TRANSFER => 'Transferencia Bancaria',
            self::PAYMENT_DIGITAL_WALLET => 'Billetera Digital',
        ];

        return $methods[$this->payment_method] ?? 'No especificado';
    }

    /**
     * Calcular el total de ítems en la orden
     */
    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    /**
     * Obtener el tiempo estimado de entrega
     */
    public function getEstimatedDeliveryAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_SHIPPED => '2-3 días hábiles',
            self::STATUS_PROCESSING => '1-2 días hábiles',
            self::STATUS_PAID => '3-5 días hábiles',
            default => 'No disponible',
        };
    }

    /**
     * Boot method para generar número de orden automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }
}
