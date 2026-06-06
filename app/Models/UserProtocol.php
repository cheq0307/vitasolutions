<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserProtocol extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'dose', 'frequency',
        'started_at', 'ended_at', 'status', 'advisor_notes',
    ];
    protected $casts = ['started_at' => 'date', 'ended_at' => 'date'];

    public function user()    { return $this->belongsTo(User::class); }
    public function product() { return $this->belongsTo(Product::class); }
    public function surveys() { return $this->hasMany(WellnessSurvey::class); }
}
