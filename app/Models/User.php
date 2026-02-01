<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password_hash',
        'full_name',
        'role_id',
        'commission_rate',
        'phone',
        'is_active',
    ];

    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    protected $casts = [
        'commission_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the password attribute name for authentication.
     */
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    /**
     * Get the password attribute name.
     */
    public function getAuthPasswordName()
    {
        return 'password_hash';
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role->name === $roleName;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is warehouse keeper.
     */
    public function isWarehouseKeeper(): bool
    {
        return $this->hasRole('warehouse_keeper');
    }

    /**
     * Check if user is salesman.
     */
    public function isSalesman(): bool
    {
        return $this->hasRole('salesman');
    }

    /**
     * Scope a query to only include active users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}