<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class HealthFile extends Model
{
    protected $fillable = [
        'user_id', 'title', 'file_type', 'cloud_url',
        'cloud_public_id', 'mime_type', 'file_size', 'study_date', 'notes',
    ];
    protected $casts = ['study_date' => 'date'];

    public function user() { return $this->belongsTo(User::class); }

    public function getFileSizeHumanAttribute(): string
    {
        if (!$this->file_size) return '';
        $kb = $this->file_size / 1024;
        return $kb >= 1024 ? round($kb / 1024, 1) . ' MB' : round($kb, 0) . ' KB';
    }
}
