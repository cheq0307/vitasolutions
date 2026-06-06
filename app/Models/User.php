<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'role', 'active'];
    protected $hidden   = ['password', 'remember_token'];
    protected $casts    = ['active' => 'boolean'];

    public function healthProfile()  { return $this->hasOne(HealthProfile::class); }
    public function healthFiles()    { return $this->hasMany(HealthFile::class); }
    public function deviceReadings() { return $this->hasMany(DeviceReading::class); }
    public function protocols()      { return $this->hasMany(UserProtocol::class); }
    public function surveys()        { return $this->hasMany(WellnessSurvey::class); }

    public function isAdmin(): bool  { return $this->role === 'admin'; }
    public function isClient(): bool { return $this->role === 'client'; }
}
