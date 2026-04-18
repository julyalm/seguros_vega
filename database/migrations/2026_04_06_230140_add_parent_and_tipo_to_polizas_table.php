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
            $table->foreignId('parent_id')->nullable()->after('id')->constrained('polizas')->onDelete('cascade');
            $table->string('tipo_movimiento')->default('emision')->after('frecuencia_pago');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('polizas', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['parent_id', 'tipo_movimiento']);
        });
    }
};
