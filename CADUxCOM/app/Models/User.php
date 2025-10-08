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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified',
        'apellido',
        'contacto',
        'ubicacion',
        'foto',
        'municipio',
        'documento_id',
        'preferencias',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
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
     * Relación: Un usuario tiene muchas reseñas
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relación: Un usuario tiene muchos puntos de fidelidad
     */
    public function loyaltyPoints()
    {
        return $this->hasMany(LoyaltyPoint::class);
    }

    /**
     * Relación: Un usuario tiene muchas órdenes
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un usuario tiene muchos logs de actividad
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Relación: Un usuario tiene muchos productos en su wishlist (many-to-many)
     */
    public function wishlists()
    {
        return $this->belongsToMany(Producto::class, 'wishlists', 'user_id', 'product_id');
    }
}
