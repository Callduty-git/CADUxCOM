<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo OrderItem - Representa un item específico dentro de una orden
 * 
 * Este modelo almacena información detallada de cada producto en una orden,
 * incluyendo snapshot de precios y datos del producto al momento de la compra.
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla en la base de datos
     */
    protected $table = 'order_items';

    /**
     * Campos que se pueden asignar de forma masiva
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'product_description',
        'empresa_id',
        'empresa_name',
        'quantity',
        'unit_price',
        'total_price',
        'product_image',
        'product_brand',
        'product_category',
        'product_subcategory',
    ];

    /**
     * Campos que deben ser convertidos a tipos específicos
     */
    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Relación: Un item pertenece a una orden
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación: Un item pertenece a un producto
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'product_id', 'Id_Producto');
    }

    /**
     * Relación: Un item pertenece a una empresa
     */
    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Obtener la imagen del producto
     */
    public function getProductImageUrlAttribute(): string
    {
        if ($this->product_image) {
            return asset('storage/' . $this->product_image);
        }
        
        return asset('images/default-product.png');
    }

    /**
     * Obtener el precio unitario formateado
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return '$' . number_format($this->unit_price, 0, ',', '.');
    }

    /**
     * Obtener el precio total formateado
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return '$' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Verificar si el producto aún existe
     */
    public function productExists(): bool
    {
        return $this->product !== null;
    }

    /**
     * Verificar si la empresa aún existe
     */
    public function empresaExists(): bool
    {
        return $this->empresa !== null;
    }

    /**
     * Obtener información del producto actual (si existe)
     */
    public function getCurrentProductInfo(): array
    {
        if (!$this->productExists()) {
            return [
                'exists' => false,
                'message' => 'Este producto ya no está disponible'
            ];
        }

        $currentProduct = $this->product;
        
        return [
            'exists' => true,
            'current_price' => $currentProduct->Precio,
            'current_stock' => $currentProduct->Cantidad,
            'price_changed' => $currentProduct->Precio != $this->unit_price,
            'stock_available' => $currentProduct->Cantidad > 0,
        ];
    }

    /**
     * Boot method para calcular el precio total automáticamente
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($orderItem) {
            // Calcular el precio total si no está establecido
            if (empty($orderItem->total_price)) {
                $orderItem->total_price = $orderItem->quantity * $orderItem->unit_price;
            }
        });
    }
}