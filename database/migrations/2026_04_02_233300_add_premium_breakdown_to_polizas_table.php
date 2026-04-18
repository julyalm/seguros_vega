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
            $table->decimal('prima_neta', 15, 2)->after('fecha_fin')->nullable();
            $table->decimal('derechos', 15, 2)->after('prima_neta')->nullable();
            $table->decimal('recargo', 15, 2)->after('derechos')->nullable();
            $table->decimal('iva', 15, 2)->after('recargo')->nullable();
            $table->decimal('comision', 15, 2)->after('prima_total')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->dropColumn(['prima_neta', 'derechos', 'recargo', 'iva', 'comision']);
        });
    }
};
