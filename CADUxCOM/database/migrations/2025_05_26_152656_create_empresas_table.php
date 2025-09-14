<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empresa extends Authenticatable
{
    use Notifiable;

    protected $table = 'empresas';

    protected $primaryKey = 'Id_Empresa'; // 👈 Clave primaria personalizada
    public $incrementing = true;          // 👈 Clave autoincremental
    protected $keyType = 'int';           // 👈 Tipo entero, no string

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
}
