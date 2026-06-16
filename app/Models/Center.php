<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Center extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'email', 'logo_url', 'active', 'owner_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // ── Relaciones ──────────────────────────────────────────────────────────

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function clients(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'client');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Logo: si no tiene, retorna null y la vista usa el logo de VitaSolutions.
     */
    public function getLogoAttribute(): ?string
    {
        return $this->logo_url ?: null;
    }

    /**
     * Verifica si un usuario es el owner de este centro.
     */
    public function isOwnedBy(User $user): bool
    {
        return $this->owner_id === $user->id;
    }
}