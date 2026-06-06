<?php
// database/migrations/2024_01_01_000003_create_health_files_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('health_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->enum('file_type', [
                'lab_result', 'prescription', 'study', 'xray', 'measurement_chart', 'other'
            ])->default('other');
            $table->string('cloud_url');
            $table->string('cloud_public_id')->nullable();
            $table->string('mime_type', 50)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->date('study_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('health_files'); }
};
