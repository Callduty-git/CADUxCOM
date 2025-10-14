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

    protected $table = 'empresas';
    protected $primaryKey = 'Id_Empresa';
    public $incrementing = true;
    protected $keyType = 'int';

    /**
     * Campos que se pueden asignar masivamente
     */
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
        // Campos de ubicación
        'latitude',
        'longitude',
        'city',
        'state',
        'postal_code',
        'country',
        'coverage_radius',
        'location_verified',
        'location_updated_at',
        // Campos de aprobación
        'status',
        'approved_at',
        'rejected_at',
        'rejection_reason',
        // Descuento progresivo
        'progressive_discount_enabled',
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
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'progressive_discount_enabled' => 'boolean',
    ];

    /**
     * Devuelve el nombre de la clave primaria usada para autenticación.
     */
    public function getAuthIdentifierName()
    {
        return $this->primaryKey;
    }

    /**
     * Devuelve el valor de la clave primaria del usuario autenticado.
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Relaciones
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'Id_Empresa', 'Id_Empresa');
    }

    public function discountRules(): HasMany
    {
        return $this->hasMany(DiscountRule::class, 'empresa_id', 'Id_Empresa');
    }

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'empresa_id', 'Id_Empresa');
    }

    /**
     * Funciones de ubicación
     */
    public function hasValidCoordinates(): bool
    {
        return !is_null($this->latitude) && !is_null($this->longitude) &&
               $this->latitude >= -90 && $this->latitude <= 90 &&
               $this->longitude >= -180 && $this->longitude <= 180;
    }

    public function getCoordinates(): ?array
    {
        if (!$this->hasValidCoordinates()) return null;

        return [
            'latitude' => (float) $this->latitude,
            'longitude' => (float) $this->longitude,
        ];
    }

    public function calculateDistanceTo(float $latitude, float $longitude): ?float
    {
        if (!$this->hasValidCoordinates()) return null;

        $earthRadius = 6371;

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) ** 2 +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) ** 2;
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    public function isWithinCoverage(float $latitude, float $longitude): bool
    {
        $distance = $this->calculateDistanceTo($latitude, $longitude);
        return $distance !== null && $distance <= $this->coverage_radius;
    }

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
            'discounted_products_count' => $this->productos()->get()->filter(fn($p) => $p->hasDiscount())->count(),
        ];
    }

    /**
     * Scopes
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

    public function scopeInCity($query, string $city)
    {
        return $query->where('city', 'like', "%{$city}%");
    }

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
