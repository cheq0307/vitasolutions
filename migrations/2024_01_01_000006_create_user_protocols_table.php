<?php
// database/migrations/2024_01_01_000006_create_user_protocols_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_protocols', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('dose');
            $table->string('frequency');
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->enum('status', ['active', 'paused', 'completed'])->default('active');
            $table->text('advisor_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void { Schema::dropIfExists('user_protocols'); }
};
