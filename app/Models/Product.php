<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     public function plans(): BelongsToMany
 {
    return $this->belongsToMany(Plan::class, 'plan_products')
        ->withPivot(['dose', 'quantity', 'schedule', 'time', 'consumption_place', 'notes'])
        ->withTimestamps();
}
    protected $fillable = ['name', 'brand', 'category', 'description', 'image_url', 'active'];
    protected $casts    = ['active' => 'boolean'];

    public function protocols() { return $this->hasMany(UserProtocol::class); }
}

