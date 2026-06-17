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
        'logo_url',         // URL externa (legacy)
        'logo_path',    // ← nuevo: ruta local
        'owner_id',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected function imagePathField(): string { return 'logo_path'; }
    protected function imageUrlField(): string  { return 'logo_url'; }

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