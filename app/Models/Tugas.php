<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'tugas',
        'tanggal_mulai',
        'tanggal_selesai',
        'status',
        'priority',
        'tugas_file',
        'karyawan_file',
        'catatan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the user that owns the tugas.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include pending tasks.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved tasks.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected tasks.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to only include submitted tasks.
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    /**
     * Check if task is overdue
     */
    public function isOverdue(): bool
    {
        return $this->tanggal_selesai < now() && $this->status !== 'approved';
    }
}
