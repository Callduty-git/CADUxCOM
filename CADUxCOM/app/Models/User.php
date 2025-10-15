<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * Los atributos que se pueden asignar en masa.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'apellido',
        'email',
        'password',
        'email_verified',
        'contacto',
        'ubicacion',
        'foto',
        'municipio',
        'documento_id',
        'preferencias',
        'role',
    ];

    /**
     * Los atributos que deben ocultarse para la serialización.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'email_verified' => 'boolean',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Un usuario tiene muchas reseñas.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación: Un usuario tiene muchos puntos de fidelidad.
     */
    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    /**
     * Relación: Un usuario tiene muchas órdenes.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un usuario tiene muchos logs de actividad.
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relación: Un usuario tiene muchos productos en su wishlist (many-to-many).
     */
    public function wishlists()
    {
        return $this->belongsToMany(Producto::class, 'wishlists', 'user_id', 'product_id');
    }

    /**
     * Relación: Un usuario tiene muchos comentarios.
     */
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    /**
     * Accesor para obtener la URL completa de la foto.
     */
    public function getFotoUrlAttribute()
    {
        return $this->foto
            ? asset('storage/' . $this->foto)
            : asset('images/default-user.png');
    }
}
