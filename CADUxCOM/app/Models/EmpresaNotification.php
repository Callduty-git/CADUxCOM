<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmpresaNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'empresa_id',
        'type',
        'title',
        'message',
        'data',
        'read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Relación: Una notificación pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Scope para obtener notificaciones no leídas
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope para obtener notificaciones leídas
     */
    public function scopeRead($query)
    {
        return $query->where('read', true);
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsRead()
    {
        $this->update([
            'read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Crear una nueva notificación para una empresa
     */
    public static function createForEmpresa($empresaId, $type, $title, $message, $data = null)
    {
        return static::create([
            'empresa_id' => $empresaId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
