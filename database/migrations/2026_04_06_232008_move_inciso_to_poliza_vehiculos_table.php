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
        Schema::table('poliza_vehiculos', function (Blueprint $table) {
            $table->integer('inciso')->nullable()->after('poliza_id');
        });

        Schema::table('polizas', function (Blueprint $table) {
            $table->dropColumn('inciso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->string('inciso')->nullable();
        });

        Schema::table('poliza_vehiculos', function (Blueprint $table) {
            $table->dropColumn('inciso');
        });
    }
};
