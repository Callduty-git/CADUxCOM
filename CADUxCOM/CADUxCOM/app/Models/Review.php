<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo Review - Representa una reseña de producto
 * 
 * Este modelo maneja las reseñas y calificaciones de productos
 * por parte de los usuarios del sistema.
 */
class Review extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'reviews';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'title',
        'content',
        'status',
        'is_verified_purchase',
        'helpful_count',
        'not_helpful_count',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'helpful_count' => 'integer',
        'not_helpful_count' => 'integer',
    ];

    /**
     * Estados válidos para una reseña
     */
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_HIDDEN = 'hidden';

    /**
     * Relación: Una reseña pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una reseña pertenece a un producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id', 'Id_Producto');
    }

    /**
     * Scope: Filtrar reseñas aprobadas
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    /**
     * Scope: Filtrar reseñas por producto
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Scope: Filtrar reseñas por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Ordenar por más útiles
     */
    public function scopeMostHelpful($query)
    {
        return $query->orderBy('helpful_count', 'desc');
    }

    /**
     * Scope: Ordenar por más recientes
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Obtener el estado de la reseña en español
     */
    public function getStatusInSpanish(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'Pendiente',
            self::STATUS_APPROVED => 'Aprobada',
            self::STATUS_REJECTED => 'Rechazada',
            self::STATUS_HIDDEN => 'Oculta',
        ];

        return $statuses[$this->status] ?? 'Desconocido';
    }

    /**
     * Obtener las estrellas como HTML
     */
    public function getStarsHtml(): string
    {
        $stars = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $stars .= '<span class="star filled">★</span>';
            } else {
                $stars .= '<span class="star empty">☆</span>';
            }
        }
        return $stars;
    }

    /**
     * Calcular el porcentaje de utilidad
     */
    public function getHelpfulnessPercentage(): float
    {
        $total = $this->helpful_count + $this->not_helpful_count;
        if ($total === 0) {
            return 0;
        }
        return round(($this->helpful_count / $total) * 100, 1);
    }

    /**
     * Verificar si la reseña puede ser editada
     */
    public function canBeEdited(): bool
    {
        return $this->status === self::STATUS_PENDING || $this->status === self::STATUS_APPROVED;
    }

    /**
     * Verificar si la reseña puede ser eliminada
     */
    public function canBeDeleted(): bool
    {
        return $this->status !== self::STATUS_REJECTED;
    }

    /**
     * Marcar como útil
     */
    public function markAsHelpful(): void
    {
        $this->increment('helpful_count');
    }

    /**
     * Marcar como no útil
     */
    public function markAsNotHelpful(): void
    {
        $this->increment('not_helpful_count');
    }

    /**
     * Aprobar reseña
     */
    public function approve(): void
    {
        $this->update(['status' => self::STATUS_APPROVED]);
    }

    /**
     * Rechazar reseña
     */
    public function reject(): void
    {
        $this->update(['status' => self::STATUS_REJECTED]);
    }

    /**
     * Ocultar reseña
     */
    public function hide(): void
    {
        $this->update(['status' => self::STATUS_HIDDEN]);
    }

    /**
     * Obtener estadísticas de reseñas para un producto
     */
    public static function getProductStats(int $productId): array
    {
        $reviews = self::byProduct($productId)->approved()->get();
        
        if ($reviews->isEmpty()) {
            return [
                'total_reviews' => 0,
                'average_rating' => 0,
                'rating_distribution' => [0, 0, 0, 0, 0],
                'verified_purchases' => 0,
            ];
        }

        $totalReviews = $reviews->count();
        $averageRating = $reviews->avg('rating');
        $verifiedPurchases = $reviews->where('is_verified_purchase', true)->count();

        // Distribución de calificaciones
        $ratingDistribution = [0, 0, 0, 0, 0];
        foreach ($reviews as $review) {
            $ratingDistribution[$review->rating - 1]++;
        }

        return [
            'total_reviews' => $totalReviews,
            'average_rating' => round($averageRating, 1),
            'rating_distribution' => $ratingDistribution,
            'verified_purchases' => $verifiedPurchases,
            'verified_percentage' => round(($verifiedPurchases / $totalReviews) * 100, 1),
        ];
    }

    /**
     * Boot method para validaciones
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            // Validar que la calificación esté entre 1 y 5
            if ($review->rating < 1 || $review->rating > 5) {
                throw new \InvalidArgumentException('La calificación debe estar entre 1 y 5 estrellas');
            }
        });
    }
}