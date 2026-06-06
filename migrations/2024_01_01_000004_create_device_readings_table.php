<?php
// database/migrations/2024_01_01_000004_create_device_readings_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('device_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('device_type', [
                'oximeter',       // SpO2 + frecuencia cardíaca
                'blood_pressure', // sistólica + diastólica
                'scale',          // peso + % grasa
                'glucometer',     // glucosa mg/dL
                'thermometer',    // temperatura °C
                'tape_measure',   // circunferencia cm
            ]);
            $table->decimal('value_1', 7, 2);
            $table->decimal('value_2', 7, 2)->nullable();
            $table->string('unit', 20)->nullable();
            $table->enum('input_method', ['manual', 'bluetooth'])->default('manual');
            $table->timestamp('measured_at');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('device_readings'); }
};
