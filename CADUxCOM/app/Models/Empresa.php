<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // O simplemente Model si no autentica
use Illuminate\Notifications\Notifiable;

class Empresa extends Authenticatable // O extends Model
{
    use HasFactory, Notifiable;

    protected $primaryKey = 'Id_Empresa'; // <-- ¡Añade o corrige esta línea!

    // Si tu clave primaria no es auto-incremental (raro para IDs)
    // public $incrementing = false;
    // protected $keyType = 'string'; // Si tu clave primaria no es un entero

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

    // Si tienes alguna relación aquí (ej: productos que pertenecen a esta empresa)
    public function productos()
    {
        return $this->hasMany(Producto::class, 'Id_Empresa', 'Id_Empresa');
        // El tercer parámetro 'Id_Empresa' es la clave local en Empresa
    }
}