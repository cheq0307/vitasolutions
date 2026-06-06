<?php
// database/migrations/2024_01_01_000002_create_health_profiles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('health_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('blood_type', ['A+','A-','B+','B-','AB+','AB-','O+','O-'])->nullable();
            $table->enum('sex', ['male', 'female', 'other'])->nullable();
            $table->date('birth_date')->nullable();
            $table->decimal('height_cm', 5, 1)->nullable();
            $table->decimal('weight_kg', 5, 2)->nullable();
            $table->text('allergies')->nullable();
            $table->text('chronic_conditions')->nullable();
            $table->text('current_medications')->nullable();
            $table->enum('main_goal', [
                'weight_loss', 'muscle_gain', 'energy',
                'sleep', 'digestion', 'general_wellness', 'other'
            ])->nullable();
            $table->enum('activity_level', [
                'sedentary', 'light', 'moderate', 'active', 'very_active'
            ])->default('sedentary')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('health_profiles'); }
};
