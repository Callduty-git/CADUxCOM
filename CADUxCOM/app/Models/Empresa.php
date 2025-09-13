<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Empresa extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'empresas'; // Asegura que apunta a la tabla correcta
    protected $primaryKey = 'Id_Empresa'; // Clave primaria personalizada
    public $incrementing = true;   // Laravel sabe que es autoincremental
    protected $keyType = 'int';    // Y que es de tipo entero

    protected $fillable = [
        'Nombre',
        'Foto',
        'Direccion',
        'Municipio',
        'Ubicacion',
        'Contacto',
        'email',
        'NIT',
        'Certificado_Camara_de_comercio',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Devuelve el nombre de la clave primaria usada para autenticación.
     * Esto evita que Laravel intente usar 'email' o 'user_id' incorrectamente.
     */
    public function getAuthIdentifierName()
    {
        return $this->primaryKey; // 'Id_Empresa'
    }

    /**
     * Devuelve el valor de la clave primaria del usuario autenticado.
     * Esto asegura que se use correctamente en la sesión.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }
}