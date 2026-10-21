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
        'is_super_admin',
        'profile_photo_path',
        'last_seen',
        'no_hp',
        'alamat',
        'bio',
        'tanggal_bergabung',
        'notifications_read_at',
        'messages_read_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_tugas' => 'boolean',
        'is_super_admin' => 'boolean',
        'deleted_at' => 'datetime',
        'notifications_read_at' => 'datetime',
        'messages_read_at' => 'datetime',
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
        return $query->where(\Illuminate\Support\Facades\DB::raw('LOWER(jabatan)'), 'admin');
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return strtolower($this->jabatan) === 'admin' || $this->isSuperAdmin();
    }

    /**
     * Check if the user is a Super Admin (Owner).
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin === true;
    }

    /**
     * Scope a query to only include employees.
     */
    public function scopeEmployees($query)
    {
        return $query->where(\Illuminate\Support\Facades\DB::raw('LOWER(jabatan)'), 'karyawan');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_tugas', true);
    }

    /**
     * Check if user is an employee.
     */
    public function isEmployee(): bool
    {
        return strtolower($this->jabatan) === 'karyawan';
    }

    /**
     * Get the profile photo URL attribute.
     */
    public function getProfilePhotoUrlAttribute(): ?string
    {
        return $this->profile_photo_path
            ? asset('storage/' . $this->profile_photo_path)
            : null;
    }
}
