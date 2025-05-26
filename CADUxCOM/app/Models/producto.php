<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';
    protected $primaryKey = 'Id_Producto';

    protected $fillable = [
        'Id_Empresa',
        'Id_Subcategoria',
        'Nombre',
        'Marca',
        'Fecha_Caducidad',
        'Cantidad',
        'Foto',
        'Descripcion',
        'Precio',
        'Tipo',
        'Codigo'
    ];

    // Relación con la empresa que publica el producto
    public function empresa()
    {
        return $this->belongsTo(Empresa::class, 'Id_Empresa');
    }

    // Relación con la subcategoría del producto
    public function subcategoria()
    {
        return $this->belongsTo(Subcategoria::class, 'Id_Subcategoria');
    }
}