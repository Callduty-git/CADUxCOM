<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'expires_at',
        'used',
        'type'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    /**
     * Generar un nuevo token de verificación
     */
    public static function generateToken($email, $type = 'password_reset', $expirationHours = 1)
    {
        // Eliminar tokens anteriores para el mismo email y tipo
        self::where('email', $email)
            ->where('type', $type)
            ->delete();

        // Crear nuevo token
        $token = Str::random(32);
        
        return self::create([
            'email' => $email,
            'token' => $token,
            'expires_at' => Carbon::now()->addHours($expirationHours),
            'type' => $type,
            'used' => false
        ]);
    }

    /**
     * Verificar si el token es válido
     */
    public static function isValidToken($email, $token, $type = 'password_reset')
    {
        $tokenRecord = self::where('email', $email)
            ->where('token', $token)
            ->where('type', $type)
            ->where('used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();

        return $tokenRecord !== null;
    }

    /**
     * Marcar token como usado
     */
    public static function markAsUsed($email, $token, $type = 'password_reset')
    {
        return self::where('email', $email)
            ->where('token', $token)
            ->where('type', $type)
            ->update(['used' => true]);
    }

    /**
     * Limpiar tokens expirados
     */
    public static function cleanExpiredTokens()
    {
        return self::where('expires_at', '<', Carbon::now())->delete();
    }

    /**
     * Relación con el usuario
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}