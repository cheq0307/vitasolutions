<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;

class Center extends Model
{
    use HasImageUrl;

    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'logo',         // URL externa (legacy)
        'logo_path',    // ← nuevo: ruta local
        'owner_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    // Override de los campos del trait para que use logo_path / logo
    protected string $imagePathField = 'logo_path';
    protected string $imageUrlField  = 'logo';

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function plans()
    {
        return $this->hasMany(Plan::class);
    }
}