<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'title',
        'message',
        'data',
        'user_id',
        'empresa_id',
        'producto_id',
        'priority',
        'channel',
        'is_read',
        'is_sent',
        'scheduled_at',
        'sent_at',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'is_sent' => 'boolean',
        'scheduled_at' => 'datetime',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
    ];

    /**
     * Relación: Una notificación pertenece a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una notificación pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Relación: Una notificación pertenece a un producto
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'Id_Producto');
    }

    /**
     * Scope: Notificaciones no leídas
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope: Notificaciones enviadas
     */
    public function scopeSent($query)
    {
        return $query->where('is_sent', true);
    }

    /**
     * Scope: Notificaciones pendientes de envío
     */
    public function scopePending($query)
    {
        return $query->where('is_sent', false);
    }

    /**
     * Scope: Notificaciones programadas
     */
    public function scopeScheduled($query)
    {
        return $query->whereNotNull('scheduled_at')
                    ->where('scheduled_at', '<=', now())
                    ->where('is_sent', false);
    }

    /**
     * Scope: Por tipo de notificación
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope: Por prioridad
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope: Por canal
     */
    public function scopeByChannel($query, string $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Marcar como leída
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Marcar como enviada
     */
    public function markAsSent(): void
    {
        $this->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);
    }

    /**
     * Verificar si está programada
     */
    public function isScheduled(): bool
    {
        return !is_null($this->scheduled_at) && $this->scheduled_at > now();
    }

    /**
     * Verificar si está lista para enviar
     */
    public function isReadyToSend(): bool
    {
        return !$this->is_sent && 
               (is_null($this->scheduled_at) || $this->scheduled_at <= now());
    }

    /**
     * Obtener tiempo transcurrido desde la creación
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Obtener clase CSS para la prioridad
     */
    public function getPriorityClassAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800 border-red-200',
            'high' => 'bg-orange-100 text-orange-800 border-orange-200',
            'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
            'low' => 'bg-gray-100 text-gray-800 border-gray-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Obtener ícono para el tipo de notificación
     */
    public function getTypeIconAttribute(): string
    {
        return match($this->type) {
            'expiry_alert' => '⏰',
            'discount_available' => '💰',
            'new_product' => '🆕',
            'order_update' => '📦',
            'wishlist_alert' => '❤️',
            'location_alert' => '📍',
            default => '🔔',
        };
    }

    /**
     * Crear notificación de alerta de caducidad
     */
    public static function createExpiryAlert(Producto $producto, int $daysUntilExpiry): self
    {
        $empresa = $producto->empresa;
        $priority = match(true) {
            $daysUntilExpiry <= 1 => 'urgent',
            $daysUntilExpiry <= 3 => 'high',
            $daysUntilExpiry <= 7 => 'medium',
            default => 'low',
        };

        $title = match($priority) {
            'urgent' => '¡Producto caduca HOY!',
            'high' => 'Producto caduca en pocos días',
            'medium' => 'Producto próximo a caducar',
            default => 'Producto con fecha de caducidad cercana',
        };

        $message = "El producto '{$producto->Nombre}' de {$empresa->Nombre} caduca en {$daysUntilExpiry} día(s). ¡Aprovecha las ofertas disponibles!";

        return self::create([
            'type' => 'expiry_alert',
            'title' => $title,
            'message' => $message,
            'data' => [
                'producto_id' => $producto->Id_Producto,
                'empresa_id' => $empresa->Id_Empresa,
                'days_until_expiry' => $daysUntilExpiry,
                'discount_info' => $producto->getDiscountInfo(),
            ],
            'empresa_id' => $empresa->Id_Empresa,
            'producto_id' => $producto->Id_Producto,
            'priority' => $priority,
            'channel' => 'in_app',
        ]);
    }

    /**
     * Crear notificación de descuento disponible
     */
    public static function createDiscountAlert(Producto $producto, array $discountInfo): self
    {
        $empresa = $producto->empresa;
        $discountPercentage = round($discountInfo['discount_percentage'], 0);

        return self::create([
            'type' => 'discount_available',
            'title' => "¡Descuento del {$discountPercentage}% disponible!",
            'message' => "El producto '{$producto->Nombre}' de {$empresa->Nombre} tiene un descuento del {$discountPercentage}% por proximidad a caducar.",
            'data' => [
                'producto_id' => $producto->Id_Producto,
                'empresa_id' => $empresa->Id_Empresa,
                'discount_info' => $discountInfo,
            ],
            'empresa_id' => $empresa->Id_Empresa,
            'producto_id' => $producto->Id_Producto,
            'priority' => 'medium',
            'channel' => 'in_app',
        ]);
    }

    /**
     * Crear notificación de nuevo producto
     */
    public static function createNewProductAlert(Producto $producto): self
    {
        $empresa = $producto->empresa;

        return self::create([
            'type' => 'new_product',
            'title' => 'Nuevo producto disponible',
            'message' => "{$empresa->Nombre} ha agregado un nuevo producto: '{$producto->Nombre}'",
            'data' => [
                'producto_id' => $producto->Id_Producto,
                'empresa_id' => $empresa->Id_Empresa,
            ],
            'empresa_id' => $empresa->Id_Empresa,
            'producto_id' => $producto->Id_Producto,
            'priority' => 'low',
            'channel' => 'in_app',
        ]);
    }

    /**
     * Crear notificación de alerta de ubicación
     */
    public static function createLocationAlert(User $user, Empresa $empresa, float $distance): self
    {
        return self::create([
            'type' => 'location_alert',
            'title' => 'Ofertas cerca de ti',
            'message' => "Hay ofertas disponibles en {$empresa->Nombre} a {$distance} km de tu ubicación.",
            'data' => [
                'empresa_id' => $empresa->Id_Empresa,
                'distance' => $distance,
                'empresa_info' => $empresa->getMapInfo(),
            ],
            'user_id' => $user->id,
            'empresa_id' => $empresa->Id_Empresa,
            'priority' => 'medium',
            'channel' => 'in_app',
        ]);
    }
}