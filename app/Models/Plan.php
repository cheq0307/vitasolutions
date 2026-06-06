<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    protected $fillable = [
        'name', 'description', 'type', 'price', 'active',
    ];

    protected $casts = [
        'active' => 'boolean',
        'price'  => 'decimal:2',
    ];

    // Etiquetas legibles
    public static array $types = [
        'monthly' => 'Plan Mensual',
        'custom'  => 'Plan Personalizado',
        'visit'   => 'Por Visita',
    ];

    public static array $schedules = [
        'breakfast' => 'Desayuno',
        'lunch'     => 'Comida',
        'dinner'    => 'Cena',
        'bedtime'   => 'Antes de dormir',
        'other'     => 'Otro',
    ];

    public static array $places = [
        'on_site'        => 'En el lugar',
        'takeaway'       => 'Para llevar',
        'sealed_package' => 'Suplemento cerrado',
    ];

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'plan_products')
            ->withPivot(['dose', 'quantity', 'schedule', 'time', 'consumption_place', 'notes'])
            ->withTimestamps();
    }

    public function clientPlans(): HasMany
    {
        return $this->hasMany(ClientPlan::class);
    }

    public function activeClients(): HasMany
    {
        return $this->hasMany(ClientPlan::class)->where('status', 'active');
    }

    // Calcula precio sumando productos (para tipo custom)
    public function calculatePrice(): float
    {
        return (float) $this->products->sum(fn($p) => $p->price * $p->pivot->quantity * 30);
    }

    public function recalculateIfCustom(): void
    {
        if ($this->type === 'custom') {
            $this->update(['price' => $this->calculatePrice()]);
        }
    }
}