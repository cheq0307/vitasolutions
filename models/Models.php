<?php
// ============================================================
// app/Models/User.php
// ============================================================
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


// ============================================================
// app/Models/HealthProfile.php
// ============================================================
// namespace App\Models;
// use Illuminate\Database\Eloquent\Model;
//
// class HealthProfile extends Model
// {
//     protected $fillable = [
//         'user_id', 'blood_type', 'sex', 'birth_date', 'height_cm', 'weight_kg',
//         'allergies', 'chronic_conditions', 'current_medications',
//         'main_goal', 'activity_level', 'notes',
//     ];
//     protected $casts = ['birth_date' => 'date'];
//
//     public function user() { return $this->belongsTo(User::class); }
//
//     // IMC calculado automáticamente — no hay columna en DB
//     public function getImcAttribute(): ?float
//     {
//         if (!$this->height_cm || !$this->weight_kg) return null;
//         $h = $this->height_cm / 100;
//         return round($this->weight_kg / ($h * $h), 1);
//     }
//
//     public function getImcLabelAttribute(): string
//     {
//         return match(true) {
//             !$this->imc        => 'Sin datos',
//             $this->imc < 18.5  => 'Bajo peso',
//             $this->imc < 25    => 'Normal',
//             $this->imc < 30    => 'Sobrepeso',
//             default            => 'Obesidad',
//         };
//     }
//
//     // Etiquetas legibles de enums
//     public function getGoalLabelAttribute(): string
//     {
//         return match($this->main_goal) {
//             'weight_loss'      => 'Bajar de peso',
//             'muscle_gain'      => 'Ganar músculo',
//             'energy'           => 'Más energía',
//             'sleep'            => 'Mejorar sueño',
//             'digestion'        => 'Mejorar digestión',
//             'general_wellness' => 'Bienestar general',
//             default            => 'Otro',
//         };
//     }
// }


// ============================================================
// app/Models/HealthFile.php
// ============================================================
// class HealthFile extends Model
// {
//     protected $fillable = [
//         'user_id', 'title', 'file_type', 'cloud_url',
//         'cloud_public_id', 'mime_type', 'file_size', 'study_date', 'notes',
//     ];
//     protected $casts = ['study_date' => 'date'];
//
//     public function user() { return $this->belongsTo(User::class); }
//
//     public function getFileSizeHumanAttribute(): string
//     {
//         if (!$this->file_size) return '';
//         $kb = $this->file_size / 1024;
//         return $kb >= 1024 ? round($kb / 1024, 1) . ' MB' : round($kb, 0) . ' KB';
//     }
//
//     public function getFileTypeLabelAttribute(): string
//     {
//         return match($this->file_type) {
//             'lab_result'        => 'Análisis de laboratorio',
//             'prescription'      => 'Receta médica',
//             'study'             => 'Estudio',
//             'xray'              => 'Radiografía',
//             'measurement_chart' => 'Tabla de mediciones',
//             default             => 'Otro',
//         };
//     }
// }


// ============================================================
// app/Models/DeviceReading.php
// ============================================================
// class DeviceReading extends Model
// {
//     protected $fillable = [
//         'user_id', 'device_type', 'value_1', 'value_2',
//         'unit', 'input_method', 'measured_at', 'notes',
//     ];
//     protected $casts = ['measured_at' => 'datetime'];
//
//     public function user() { return $this->belongsTo(User::class); }
//
//     public function getDeviceLabelAttribute(): string
//     {
//         return match($this->device_type) {
//             'oximeter'       => 'Oxímetro',
//             'blood_pressure' => 'Tensiómetro',
//             'scale'          => 'Báscula',
//             'glucometer'     => 'Glucómetro',
//             'thermometer'    => 'Termómetro',
//             'tape_measure'   => 'Cinta métrica',
//             default          => 'Dispositivo',
//         };
//     }
//
//     // "120 / 80 mmHg" o "98 %"
//     public function getReadingDisplayAttribute(): string
//     {
//         return $this->value_2
//             ? "{$this->value_1} / {$this->value_2} {$this->unit}"
//             : "{$this->value_1} {$this->unit}";
//     }
// }


// ============================================================
// app/Models/Product.php
// ============================================================
// class Product extends Model
// {
//     protected $fillable = ['name', 'brand', 'category', 'description', 'image_url', 'active'];
//     protected $casts    = ['active' => 'boolean'];
//
//     public function protocols() { return $this->hasMany(UserProtocol::class); }
// }


// ============================================================
// app/Models/UserProtocol.php
// ============================================================
// class UserProtocol extends Model
// {
//     protected $fillable = [
//         'user_id', 'product_id', 'dose', 'frequency',
//         'started_at', 'ended_at', 'status', 'advisor_notes',
//     ];
//     protected $casts = ['started_at' => 'date', 'ended_at' => 'date'];
//
//     public function user()    { return $this->belongsTo(User::class); }
//     public function product() { return $this->belongsTo(Product::class); }
//     public function surveys() { return $this->hasMany(WellnessSurvey::class); }
//
//     public function beforeSurvey()
//     {
//         return $this->hasOne(WellnessSurvey::class)->where('survey_type', 'before');
//     }
// }


// ============================================================
// app/Models/WellnessSurvey.php
// ============================================================
// class WellnessSurvey extends Model
// {
//     protected $fillable = [
//         'user_id', 'user_protocol_id', 'survey_type',
//         'energy_level', 'sleep_quality', 'stress_level',
//         'mood', 'digestion', 'symptoms', 'free_notes', 'answered_at',
//     ];
//     protected $casts = ['answered_at' => 'datetime'];
//
//     public function user()     { return $this->belongsTo(User::class); }
//     public function protocol() { return $this->belongsTo(UserProtocol::class, 'user_protocol_id'); }
//
//     // Promedio de los 4 indicadores (1-10)
//     public function getWellnessScoreAttribute(): float
//     {
//         $fields = array_filter([
//             $this->energy_level, $this->sleep_quality,
//             $this->stress_level, $this->mood,
//         ]);
//         return count($fields) ? round(array_sum($fields) / count($fields), 1) : 0;
//     }
// }
