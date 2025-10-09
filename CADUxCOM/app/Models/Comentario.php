<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'contenido',
        'producto_id',
        'user_id',
        'empresa_id',
        'parent_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un comentario pertenece a un producto
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id', 'Id_Producto');
    }

    /**
     * Relación: Un comentario pertenece a un usuario (opcional)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un comentario pertenece a una empresa (opcional)
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Relación: Un comentario puede tener un comentario padre (para respuestas)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comentario::class, 'parent_id');
    }

    /**
     * Relación: Un comentario puede tener muchas respuestas
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comentario::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    /**
     * Obtener el autor del comentario (usuario o empresa)
     */
    public function getAuthorAttribute()
    {
        if ($this->user_id) {
            return $this->user;
        }
        return $this->empresa;
    }

    /**
     * Obtener el nombre del autor
     */
    public function getAuthorNameAttribute(): string
    {
        if ($this->user_id) {
            return $this->user->name ?? 'Usuario';
        }
        return $this->empresa->Nombre ?? 'Empresa';
    }

    /**
     * Verificar si es una respuesta
     */
    public function isReply(): bool
    {
        return !is_null($this->parent_id);
    }

    /**
     * Verificar si es un comentario principal
     */
    public function isMainComment(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Obtener el tipo de autor
     */
    public function getAuthorTypeAttribute(): string
    {
        if ($this->user_id) {
            return 'user';
        }
        return 'empresa';
    }

    /**
     * Scope para comentarios principales
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope para respuestas
     */
    public function scopeReplies($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Scope para comentarios de un producto específico
     */
    public function scopeForProduct($query, $productoId)
    {
        return $query->where('producto_id', $productoId);
    }
}
