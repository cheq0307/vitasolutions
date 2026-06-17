<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Productos: agregar image_path para almacenamiento local
        // image_url sigue existiendo para compatibilidad con URL externas
        Schema::table('products', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('image_url');
        });

        // Centros: agregar logo_path para almacenamiento local
        Schema::table('centers', function (Blueprint $table) {
            $table->string('logo_path')->nullable()->after('logo');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });

        Schema::table('centers', function (Blueprint $table) {
            $table->dropColumn('logo_path');
        });
    }
};