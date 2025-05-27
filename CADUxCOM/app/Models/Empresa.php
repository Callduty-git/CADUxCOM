<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Empresa extends Authenticatable
{
    use HasFactory;

    protected $table = 'empresas'; // opcional si sigue la convención

    protected $fillable = [
        'Id_Inventario',
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
}