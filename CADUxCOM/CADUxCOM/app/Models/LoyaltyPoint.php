<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo LoyaltyPoint - Representa puntos de fidelidad
 * 
 * Este modelo maneja el sistema de puntos de fidelidad
 * para recompensar a los usuarios por sus compras.
 */
class LoyaltyPoint extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'loyalty_points';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'type',
        'points',
        'description',
        'expires_at',
        'status',
        'notes',
        'reference',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'points' => 'integer',
        'expires_at' => 'date',
    ];

    /**
     * Tipos de transacciones de puntos
     */
    const TYPE_EARNED = 'earned';
    const TYPE_REDEEMED = 'redeemed';
    const TYPE_EXPIRED = 'expired';
    const TYPE_ADJUSTED = 'adjusted';

    /**
     * Estados de los puntos
     */
    const STATUS_ACTIVE = 'active';
    const STATUS_EXPIRED = 'expired';
    const STATUS_REDEEMED = 'redeemed';

    /**
     * Relación: Los puntos pertenecen a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Los puntos pueden estar relacionados con una orden
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Filtrar puntos activos
     */
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Scope: Filtrar por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filtrar puntos ganados
     */
    public function scopeEarned($query)
    {
        return $query->where('type', self::TYPE_EARNED);
    }

    /**
     * Scope: Filtrar puntos canjeados
     */
    public function scopeRedeemed($query)
    {
        return $query->where('type', self::TYPE_REDEEMED);
    }

    /**
     * Scope: Filtrar puntos que no han expirado
     */
    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Obtener el tipo de transacción en español
     */
    public function getTypeInSpanish(): string
    {
        $types = [
            self::TYPE_EARNED => 'Ganados',
            self::TYPE_REDEEMED => 'Canjeados',
            self::TYPE_EXPIRED => 'Expirados',
            self::TYPE_ADJUSTED => 'Ajustados',
        ];

        return $types[$this->type] ?? 'Desconocido';
    }

    /**
     * Obtener el estado en español
     */
    public function getStatusInSpanish(): string
    {
        $statuses = [
            self::STATUS_ACTIVE => 'Activos',
            self::STATUS_EXPIRED => 'Expirados',
            self::STATUS_REDEEMED => 'Canjeados',
        ];

        return $statuses[$this->status] ?? 'Desconocido';
    }

    /**
     * Verificar si los puntos han expirado
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Verificar si los puntos están activos
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE && !$this->isExpired();
    }

    /**
     * Obtener días hasta la expiración
     */
    public function getDaysUntilExpiration(): ?int
    {
        if (!$this->expires_at) {
            return null;
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Ganar puntos por una compra
     */
    public static function earnFromOrder(Order $order, int $pointsPerPeso = 1): self
    {
        $points = (int) ($order->total_amount * $pointsPerPeso / 1000); // 1 punto por cada $1000
        
        return self::create([
            'user_id' => $order->user_id,
            'order_id' => $order->id,
            'type' => self::TYPE_EARNED,
            'points' => $points,
            'description' => "Puntos ganados por compra #{$order->order_number}",
            'expires_at' => now()->addYear(), // Los puntos expiran en 1 año
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Canjear puntos por descuento
     */
    public static function redeemForDiscount(int $userId, int $points, string $description): self
    {
        return self::create([
            'user_id' => $userId,
            'type' => self::TYPE_REDEEMED,
            'points' => -$points, // Negativo para canje
            'description' => $description,
            'status' => self::STATUS_REDEEMED,
        ]);
    }

    /**
     * Obtener el total de puntos activos de un usuario
     */
    public static function getTotalActivePoints(int $userId): int
    {
        return self::byUser($userId)
            ->active()
            ->notExpired()
            ->sum('points');
    }

    /**
     * Obtener el historial de puntos de un usuario
     */
    public static function getUserHistory(int $userId, int $limit = 20): \Illuminate\Database\Eloquent\Collection
    {
        return self::byUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Obtener estadísticas de puntos de un usuario
     */
    public static function getUserStats(int $userId): array
    {
        $totalEarned = self::byUser($userId)->earned()->sum('points');
        $totalRedeemed = abs(self::byUser($userId)->redeemed()->sum('points'));
        $activePoints = self::getTotalActivePoints($userId);
        $expiredPoints = self::byUser($userId)
            ->where('status', self::STATUS_EXPIRED)
            ->sum('points');

        return [
            'total_earned' => $totalEarned,
            'total_redeemed' => $totalRedeemed,
            'active_points' => $activePoints,
            'expired_points' => $expiredPoints,
            'lifetime_points' => $totalEarned,
        ];
    }

    /**
     * Marcar puntos como expirados
     */
    public function markAsExpired(): void
    {
        $this->update(['status' => self::STATUS_EXPIRED]);
    }

    /**
     * Boot method para manejar expiración automática
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($loyaltyPoint) {
            // Si los puntos han expirado, marcarlos como expirados
            if ($loyaltyPoint->isExpired() && $loyaltyPoint->status === self::STATUS_ACTIVE) {
                $loyaltyPoint->status = self::STATUS_EXPIRED;
            }
        });
    }
}