<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Center extends Model
{
    protected $fillable = [
        'name', 'address', 'phone', 'email', 'logo_url', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Usuarios que pertenecen a este centro
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    // Solo admins del centro
    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    // Solo clientes del centro
    public function clients(): HasMany
    {
        return $this->hasMany(User::class)->where('role', 'client');
    }

    // Productos del centro
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    // Planes del centro
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    // Logo: si no tiene, retorna null y la vista usa el logo de VitaSolutions
    public function getLogoAttribute(): ?string
    {
        return $this->logo_url ?: null;
    }
}