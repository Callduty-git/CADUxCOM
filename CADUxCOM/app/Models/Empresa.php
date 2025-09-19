<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'latitude',
        'longitude',
        'city',
        'state',
        'postal_code',
        'country',
        'coverage_radius',
        'location_verified',
        'location_updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'coverage_radius' => 'integer',
        'location_verified' => 'boolean',
        'location_updated_at' => 'datetime',
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

    /**
     * Relación: Una empresa tiene muchos productos
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'Id_Empresa', 'Id_Empresa');
    }

    /**
     * Relación: Una empresa tiene muchas reglas de descuento
     */
    public function discountRules(): HasMany
    {
        return $this->hasMany(DiscountRule::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Verificar si la empresa tiene coordenadas válidas
     */
    public function hasValidCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude) &&
               $this->latitude >= -90 && $this->latitude <= 90 &&
               $this->longitude >= -180 && $this->longitude <= 180;
    }

    /**
     * Obtener coordenadas como array
     */
    public function getCoordinates(): ?array
    {
        if (!$this->hasValidCoordinates()) {
            return null;
        }

        return [
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
        ];
    }

    /**
     * Calcular distancia a otra ubicación usando la fórmula de Haversine
     */
    public function calculateDistanceTo(float $latitude, float $longitude): ?float
    {
        if (!$this->hasValidCoordinates()) {
            return null;
        }

        $earthRadius = 6371; // Radio de la Tierra en kilómetros

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Verificar si una ubicación está dentro del radio de cobertura
     */
    public function isWithinCoverage(float $latitude, float $longitude): bool
    {
        $distance = $this->calculateDistanceTo($latitude, $longitude);
        
        if ($distance === null) {
            return false;
        }

        return $distance <= $this->coverage_radius;
    }

    /**
     * Obtener dirección completa formateada
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->Direccion,
            $this->city,
            $this->state,
            $this->postal_code,
            $this->country
        ]);

        return implode(', ', $parts);
    }

    /**
     * Obtener información de ubicación para mostrar en el mapa
     */
    public function getMapInfo(): array
    {
        return [
            'id' => $this->Id_Empresa,
            'name' => $this->Nombre,
            'address' => $this->getFullAddress(),
            'coordinates' => $this->getCoordinates(),
            'coverage_radius' => $this->coverage_radius,
            'location_verified' => $this->location_verified,
            'products_count' => $this->productos()->count(),
            'discounted_products_count' => $this->productos()->get()->filter(function ($producto) {
                return $producto->hasDiscount();
            })->count(),
        ];
    }

    /**
     * Scope: Filtrar empresas con coordenadas válidas
     */
    public function scopeWithValidCoordinates($query)
    {
        return $query->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where('latitude', '>=', -90)
                    ->where('latitude', '<=', 90)
                    ->where('longitude', '>=', -180)
                    ->where('longitude', '<=', 180);
    }

    /**
     * Scope: Filtrar empresas por proximidad
     */
    public function scopeNearTo($query, float $latitude, float $longitude, float $radiusKm = 10)
    {
        return $query->withValidCoordinates()
                    ->selectRaw('*, (
                        6371 * acos(
                            cos(radians(?)) * cos(radians(latitude)) * 
                            cos(radians(longitude) - radians(?)) + 
                            sin(radians(?)) * sin(radians(latitude))
                        )
                    ) AS distance', [$latitude, $longitude, $latitude])
                    ->having('distance', '<=', $radiusKm)
                    ->orderBy('distance');
    }

    /**
     * Scope: Filtrar empresas por ciudad
     */
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

    /**
     * Scope: Filtrar empresas verificadas
     */
    public function scopeVerified($query)
    {
        return $query->where('location_verified', true);
    }

    /**
     * Actualizar coordenadas y marcar como verificada
     */
    public function updateLocation(float $latitude, float $longitude, array $addressData = []): void
    {
        $this->update([
            'latitude' => $latitude,
            'longitude' => $longitude,
            'city' => $addressData['city'] ?? null,
            'state' => $addressData['state'] ?? null,
            'postal_code' => $addressData['postal_code'] ?? null,
            'country' => $addressData['country'] ?? 'Colombia',
            'location_verified' => true,
            'location_updated_at' => now(),
        ]);
    }
}