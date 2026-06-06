<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class WellnessSurvey extends Model
{
    protected $fillable = [
        'user_id', 'user_protocol_id', 'survey_type',
        'energy_level', 'sleep_quality', 'stress_level',
        'mood', 'digestion', 'symptoms', 'free_notes', 'answered_at',
    ];
    protected $casts = ['answered_at' => 'datetime'];

    public function user()     { return $this->belongsTo(User::class); }
    public function protocol() { return $this->belongsTo(UserProtocol::class, 'user_protocol_id'); }

    public function getWellnessScoreAttribute(): float
    {
        $fields = array_filter([
            $this->energy_level, $this->sleep_quality,
            $this->stress_level, $this->mood,
        ]);
        return count($fields) ? round(array_sum($fields) / count($fields), 1) : 0;
    }
}
