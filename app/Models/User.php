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

    public function isStrictAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Verifica si el usuario es el admin owner de su centro.
     * El owner es quien tiene su id como owner_id en la tabla centers.
     */
    public function isOwner(): bool
    {
        if (!$this->isStrictAdmin() || !$this->center_id) {
            return false;
        }

        return $this->center && $this->center->owner_id === $this->id;
    }

    /**
     * Verifica si el usuario es admin staff (no owner).
     * Los staff son admins de un centro pero no el dueño.
     */
    public function isStaff(): bool
    {
        return $this->isStrictAdmin() && !$this->isOwner();
    }
}