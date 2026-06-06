<?php
// database/migrations/2024_01_01_000007_create_wellness_surveys_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wellness_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_protocol_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('survey_type', ['before', 'weekly', 'biweekly', 'after']);
            $table->tinyInteger('energy_level')->nullable();
            $table->tinyInteger('sleep_quality')->nullable();
            $table->tinyInteger('stress_level')->nullable();
            $table->tinyInteger('mood')->nullable();
            $table->enum('digestion', ['good', 'regular', 'bad'])->nullable();
            $table->text('symptoms')->nullable();
            $table->text('free_notes')->nullable();
            $table->timestamp('answered_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('wellness_surveys'); }
};
