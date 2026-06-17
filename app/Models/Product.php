<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;

class Product extends Model
{
    use HasImageUrl;

    protected $fillable = [
        'center_id',
        'name',
        'brand',
        'category',
        'description',
        'price',
        'image_url',
        'image_path',   // ← nuevo: ruta local
        'is_active',
        'is_suggested',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'is_suggested' => 'boolean',
        'price'        => 'decimal:2',
    ];

    // HasImageUrl usa 'image_path' e 'image_url' por defecto — coincide perfecto.

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function center()
    {
        return $this->belongsTo(Center::class);
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_products')
                    ->withPivot('dose', 'quantity', 'schedule', 'time', 'consumption_place')
                    ->withTimestamps();
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCenter($query, int $centerId)
    {
        return $query->where('center_id', $centerId);
    }

    public function scopeSuggested($query)
    {
        return $query->where('is_suggested', true);
    }
}