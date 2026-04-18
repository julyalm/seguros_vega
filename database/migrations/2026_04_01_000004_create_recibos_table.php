<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('recibos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poliza_id')->constrained()->onDelete('cascade');
            $table->integer('indice_recibo');
            $table->decimal('monto', 15, 2);
            $table->decimal('comision', 5, 2)->default(0);
            $table->string('status')->default('pendiente');
            $table->decimal('contracargo', 15, 2)->default(0);
            $table->date('fecha_vencimiento')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recibos');
    }
};
