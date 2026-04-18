<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->foreignId('asegurado_direccion_id')->nullable()->after('asegurado_id')->constrained('asegurado_direcciones');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->dropForeign(['asegurado_direccion_id']);
            $table->dropColumn('asegurado_direccion_id');
        });
    }
};
