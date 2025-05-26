<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;

    protected $table = 'categorias';
    protected $primaryKey = 'Id_Categoria';
    protected $fillable = ['Nombre', 'Icono'];

    public function subcategorias()
    {
        return $this->hasMany(Subcategoria::class, 'Id_Categoria');
    }
}