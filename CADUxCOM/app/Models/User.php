<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
     * Get the attributes that should be cast.
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
}
