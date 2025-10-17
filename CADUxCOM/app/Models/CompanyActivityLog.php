<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'action',
        'description',
        'data',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'method'
    ];

    protected $casts = [
        'data' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the company that owns the activity log.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Empresa::class, 'company_id', 'Id_Empresa');
    }

    /**
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include activities of a given action.
     */
    public function scopeAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by company.
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Create a new company activity log entry.
     */
    public static function logActivity($companyId, $action, $description, $data = null, $userId = null)
    {
        return self::create([
            'company_id' => $companyId,
            'action' => $action,
            'description' => $description,
            'data' => $data,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method()
        ]);
    }
}