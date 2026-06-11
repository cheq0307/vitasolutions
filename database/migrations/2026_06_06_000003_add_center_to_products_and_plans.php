<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Productos por centro + flag de sugerido por superadmin
        Schema::table('products', function (Blueprint $table) {
            $table->foreignId('center_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->boolean('is_suggested')->default(false)->after('active'); // superadmin puede marcar como sugerido
        });

        // Planes por centro
        Schema::table('plans', function (Blueprint $table) {
            $table->foreignId('center_id')->nullable()->after('id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['center_id']);
            $table->dropColumn(['center_id', 'is_suggested']);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['center_id']);
            $table->dropColumn('center_id');
        });
    }
};