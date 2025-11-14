<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'subject_type',
        'subject_id',
        'description',
        'properties',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user that owns the activity log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subject model.
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Log an activity.
     * 
     * @param string $action The action performed
     * @param \Illuminate\Database\Eloquent\Model|null $subject The subject model
     * @param string|null $description Human readable description
     * @param array<string, mixed> $properties Additional properties
     * @return static
     */
    public static function log(string $action, $subject = null, ?string $description = null, array $properties = []): self
    {
        $subjectId = null;
        if ($subject && method_exists($subject, 'getKey')) {
            /** @var \Illuminate\Database\Eloquent\Model $subject */
            $subjectId = $subject->getKey();
        }

        return static::create([
            'user_id' => optional(auth('sanctum')->user())->id,
            'action' => $action,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subjectId,
            'description' => $description,
            'properties' => $properties,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
