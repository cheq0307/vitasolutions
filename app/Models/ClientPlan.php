<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPlan extends Model
{
    protected $fillable = [
        'user_id', 'plan_id', 'starts_at', 'ends_at', 'status', 'notes',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at'   => 'date',
    ];

    public static array $statuses = [
        'active'    => 'Activo',
        'paused'    => 'Pausado',
        'cancelled' => 'Cancelado',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function daysRemaining(): ?int
    {
        return $this->ends_at ? now()->diffInDays($this->ends_at, false) : null;
    }
}