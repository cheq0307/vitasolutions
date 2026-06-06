<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DeviceReading extends Model
{
    protected $fillable = [
        'user_id', 'device_type', 'value_1', 'value_2',
        'unit', 'input_method', 'measured_at', 'notes',
    ];
    protected $casts = ['measured_at' => 'datetime'];

    public function user() { return $this->belongsTo(User::class); }

    public function getDeviceLabelAttribute(): string
    {
        return match($this->device_type) {
            'oximeter'       => 'Oxímetro',
            'blood_pressure' => 'Tensiómetro',
            'scale'          => 'Báscula',
            'glucometer'     => 'Glucómetro',
            'thermometer'    => 'Termómetro',
            'tape_measure'   => 'Cinta métrica',
            default          => 'Dispositivo',
        };
    }

    public function getReadingDisplayAttribute(): string
    {
        return $this->value_2
            ? "{$this->value_1} / {$this->value_2} {$this->unit}"
            : "{$this->value_1} {$this->unit}";
    }
}
