<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    protected $fillable = [
        'nama',
        'email',
        'jabatan',
        'password',
        'is_tugas',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_tugas' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the tasks for the user.
     */
    public function tugas(): HasMany
    {
        return $this->hasMany(Tugas::class);
    }

    /**
     * Scope a query to only include admins.
     */
    public function scopeAdmins($query)
    {
        return $query->where('jabatan', 'Admin');
    }

    /**
     * Scope a query to only include employees.
     */
    public function scopeEmployees($query)
    {
        return $query->where('jabatan', 'Karyawan');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_tugas', true);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->jabatan === 'Admin';
    }

    /**
     * Check if user is an employee.
     */
    public function isEmployee(): bool
    {
        return $this->jabatan === 'Karyawan';
    }
}
