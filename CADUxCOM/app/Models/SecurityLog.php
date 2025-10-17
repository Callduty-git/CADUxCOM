<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'description',
        'data',
        'user_id',
        'ip_address',
        'user_agent',
        'url',
        'method',
        'severity',
        'resolved',
        'resolution_notes',
        'resolved_at',
        'resolved_by'
    ];

    protected $casts = [
        'data' => 'array',
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the security log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user that resolved the security issue.
     */
    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    /**
     * Scope a query to only include logs of a given type.
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope a query to only include logs of a given severity.
     */
    public function scopeSeverity($query, $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope a query to only include resolved/unresolved logs.
     */
    public function scopeResolved($query, $resolved = true)
    {
        return $query->where('resolved', $resolved);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Create a new security log entry.
     */
    public static function logSecurity($type, $description, $severity = 'medium', $data = null, $userId = null)
    {
        return self::create([
            'type' => $type,
            'description' => $description,
            'severity' => $severity,
            'data' => $data,
            'user_id' => $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'resolved' => false
        ]);
    }

    /**
     * Mark the security issue as resolved.
     */
    public function markAsResolved($resolutionNotes = null, $resolvedBy = null)
    {
        $this->update([
            'resolved' => true,
            'resolution_notes' => $resolutionNotes,
            'resolved_at' => now(),
            'resolved_by' => $resolvedBy
        ]);
    }
}