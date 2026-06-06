<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', ['monthly', 'custom', 'visit'])->default('monthly');
            $table->decimal('price', 8, 2)->default(0); // manual para monthly, calculado para custom
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('plan_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('dose')->nullable();               // ej. "1 cápsula", "2 tabletas"
            $table->unsignedTinyInteger('quantity')->default(1);
            $table->enum('schedule', ['breakfast', 'lunch', 'dinner', 'bedtime', 'other'])->default('breakfast');
            $table->time('time')->nullable();                 // hora exacta opcional
            $table->enum('consumption_place', ['on_site', 'takeaway', 'sealed_package'])->default('on_site');
            $table->string('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('client_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->enum('status', ['active', 'paused', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_plans');
        Schema::dropIfExists('plan_products');
        Schema::dropIfExists('plans');
    }
};