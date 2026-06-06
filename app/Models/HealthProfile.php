<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HealthProfile extends Model
{
    protected $fillable = [
        'user_id',
        'blood_type',
        'sex',
        'birth_date',
        'height_cm',
        'weight_kg',
        'allergies',
        'chronic_conditions',
        'current_medications',
        'main_goal',
        'activity_level',
        'notes',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'height_cm'  => 'float',
        'weight_kg'  => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImcAttribute(): ?float
    {
        if ($this->height_cm && $this->weight_kg) {
            $height_m = $this->height_cm / 100;
            return round($this->weight_kg / ($height_m * $height_m), 1);
        }
        return null;
    }

    public function getImcLabelAttribute(): string
    {
        $imc = $this->imc;
        if (!$imc) return '—';
        if ($imc < 18.5) return 'Bajo peso';
        if ($imc < 25)   return 'Normal';
        if ($imc < 30)   return 'Sobrepeso';
        if ($imc < 35)   return 'Obesidad I';
        if ($imc < 40)   return 'Obesidad II';
        return 'Obesidad III';
    }
}