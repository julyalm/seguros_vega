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
        Schema::table('recibos', function (Blueprint $table) {
            $table->decimal('prima_neta', 15, 2)->nullable()->after('indice_recibo');
            $table->decimal('derechos', 15, 2)->nullable()->after('prima_neta');
            $table->decimal('recargo', 15, 2)->nullable()->after('derechos');
            $table->decimal('iva', 15, 2)->nullable()->after('recargo');
            $table->date('fecha_inicio_vigencia')->nullable()->after('iva');
            $table->date('fecha_fin_vigencia')->nullable()->after('fecha_inicio_vigencia');
            $table->integer('periodo_gracia')->default(30)->after('fecha_fin_vigencia');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('recibos', function (Blueprint $table) {
            $table->dropColumn(['prima_neta', 'derechos', 'recargo', 'iva', 'fecha_inicio_vigencia', 'fecha_fin_vigencia', 'periodo_gracia']);
        });
    }
};
