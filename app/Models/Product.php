<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasImageUrl;

class Product extends Model
{
    use HasImageUrl;

    protected $fillable = [
        'center_id',
        'sku',
        'name',
        'brand',
        'category',
        'description',
        'price',
        'cost',
        'image_url',
        'image_path',
        'active',
        'is_suggested',
        'stock',
    ];

    protected $casts = [
        'active'       => 'boolean',
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
        return $query->where('active', true);
    }

    public function scopeForCenter($query, int $centerId)
    {
        return $query->where('center_id', $centerId);
    }

    public function scopeSuggested($query)
    {
        return $query->where('is_suggested', true);
    }

    // ─── Accessors ──────────────────────────────────────────────────────────

    public function getGainAttribute()
    {
        if (!$this->cost || !$this->price) {
            return 0;
        }
        return $this->price - $this->cost;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock === 0) {
            return 'agotado';
        } elseif ($this->stock <= 5) {
            return 'bajo';
        }
        return 'disponible';
    }
}