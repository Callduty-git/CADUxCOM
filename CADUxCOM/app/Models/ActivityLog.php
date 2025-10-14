<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo ActivityLog - Representa logs de actividad del sistema
 * 
 * Este modelo registra todas las acciones importantes realizadas
 * por usuarios y empresas dentro del sistema CADUxCOM.
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'activity_logs';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'user_id',
        'empresa_id',
        'action_type',
        'description',
        'metadata',
        'ip_address',
        'user_agent',
        'url',
        'method',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'metadata' => 'array',
    ];

    /**
     * Tipos de acciones que se pueden registrar
     */
    const ACTION_USER_LOGIN = 'user_login';
    const ACTION_USER_REGISTER = 'user_register';
    const ACTION_USER_LOGOUT = 'user_logout';
    const ACTION_PRODUCT_VIEW = 'product_view';
    const ACTION_PRODUCT_CREATE = 'product_create';
    const ACTION_PRODUCT_UPDATE = 'product_update';
    const ACTION_PRODUCT_DELETE = 'product_delete';
    const ACTION_ORDER_CREATE = 'order_create';
    const ACTION_ORDER_UPDATE = 'order_update';
    const ACTION_ORDER_CANCEL = 'order_cancel';
    const ACTION_REVIEW_CREATE = 'review_create';
    const ACTION_REVIEW_UPDATE = 'review_update';
    const ACTION_CART_ADD = 'cart_add';
    const ACTION_CART_REMOVE = 'cart_remove';
    const ACTION_WISHLIST_ADD = 'wishlist_add';
    const ACTION_WISHLIST_REMOVE = 'wishlist_remove';
    const ACTION_COUPON_APPLY = 'coupon_apply';
    const ACTION_SEARCH_PERFORMED = 'search_performed';
    const ACTION_PAGE_VIEW = 'page_view';

    /**
     * Relación: Un log puede pertenecer a un usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un log puede pertenecer a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Scope: Filtrar por usuario
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Filtrar por empresa
     */
    public function scopeByEmpresa($query, $empresaId)
    {
        return $query->where('empresa_id', $empresaId);
    }

    /**
     * Scope: Filtrar por tipo de acción
     */
    public function scopeByAction($query, $actionType)
    {
        return $query->where('action_type', $actionType);
    }

    /**
     * Scope: Filtrar por rango de fechas
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope: Filtrar por IP
     */
    public function scopeByIp($query, $ipAddress)
    {
        return $query->where('ip_address', $ipAddress);
    }

    /**
     * Obtener el tipo de acción en español
     */
    public function getActionTypeInSpanish(): string
    {
        $actions = [
            self::ACTION_USER_LOGIN => 'Inicio de sesión',
            self::ACTION_USER_REGISTER => 'Registro de usuario',
            self::ACTION_USER_LOGOUT => 'Cierre de sesión',
            self::ACTION_PRODUCT_VIEW => 'Visualización de producto',
            self::ACTION_PRODUCT_CREATE => 'Creación de producto',
            self::ACTION_PRODUCT_UPDATE => 'Actualización de producto',
            self::ACTION_PRODUCT_DELETE => 'Eliminación de producto',
            self::ACTION_ORDER_CREATE => 'Creación de orden',
            self::ACTION_ORDER_UPDATE => 'Actualización de orden',
            self::ACTION_ORDER_CANCEL => 'Cancelación de orden',
            self::ACTION_REVIEW_CREATE => 'Creación de reseña',
            self::ACTION_REVIEW_UPDATE => 'Actualización de reseña',
            self::ACTION_CART_ADD => 'Agregar al carrito',
            self::ACTION_CART_REMOVE => 'Eliminar del carrito',
            self::ACTION_WISHLIST_ADD => 'Agregar a lista de deseos',
            self::ACTION_WISHLIST_REMOVE => 'Eliminar de lista de deseos',
            self::ACTION_COUPON_APPLY => 'Aplicar cupón',
            self::ACTION_SEARCH_PERFORMED => 'Búsqueda realizada',
            self::ACTION_PAGE_VIEW => 'Visualización de página',
        ];

        return $actions[$this->action_type] ?? 'Acción desconocida';
    }

    /**
     * Obtener el nombre del actor (usuario o empresa)
     */
    public function getActorName(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        if ($this->empresa) {
            return $this->empresa->Nombre;
        }

        return 'Sistema';
    }

    /**
     * Obtener el tipo de actor
     */
    public function getActorType(): string
    {
        if ($this->user) {
            return 'Usuario';
        }

        if ($this->empresa) {
            return 'Empresa';
        }

        return 'Sistema';
    }

    /**
     * Crear un log de actividad
     */
    public static function log(
        string $actionType,
        string $description,
        array $metadata = [],
        ?int $userId = null,
        ?int $empresaId = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'empresa_id' => $empresaId,
            'action_type' => $actionType,
            'description' => $description,
            'metadata' => $metadata,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
        ]);
    }

    /**
     * Obtener estadísticas de actividad
     */
    public static function getActivityStats(?int $userId = null, ?int $empresaId = null, int $days = 30): array
    {
        $query = self::query();

        if ($userId) {
            $query->byUser($userId);
        }

        if ($empresaId) {
            $query->byEmpresa($empresaId);
        }

        $startDate = now()->subDays($days);
        $endDate = now();

        $totalActivities = $query->byDateRange($startDate, $endDate)->count();

        $activitiesByType = $query->byDateRange($startDate, $endDate)
            ->selectRaw('action_type, COUNT(*) as count')
            ->groupBy('action_type')
            ->pluck('count', 'action_type')
            ->toArray();

        $activitiesByDay = $query->byDateRange($startDate, $endDate)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        return [
            'total_activities' => $totalActivities,
            'activities_by_type' => $activitiesByType,
            'activities_by_day' => $activitiesByDay,
            'period_days' => $days,
        ];
    }

    /**
     * Limpiar logs antiguos
     */
    public static function cleanOldLogs(int $daysToKeep = 90): int
    {
        $cutoffDate = now()->subDays($daysToKeep);
        return self::where('created_at', '<', $cutoffDate)->delete();
    }

    /**
     * Boot method para limpieza automática
     */
    protected static function boot()
    {
        parent::boot();

        // Limpia logs antiguos automáticamente cada 1000 registros
        static::created(function () {
            static $count = 0;
            $count++;

            if ($count % 1000 === 0) {
                self::cleanOldLogs();
            }
        });
    }
}
