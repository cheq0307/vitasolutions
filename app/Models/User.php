<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'center_id', 'phone', 'active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'active'            => 'boolean',
    ];

    // ── Relaciones ──────────────────────────────────────────────────────────

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function healthProfile(): HasOne
    {
        return $this->hasOne(HealthProfile::class);
    }

    public function clientPlans(): HasMany
    {
        return $this->hasMany(ClientPlan::class);
    }

    // ── Helpers de rol ──────────────────────────────────────────────────────

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        // superadmin también tiene permisos de admin
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    public function isStrictAdmin(): bool
    {
        return $this->role === 'admin';
    }
}