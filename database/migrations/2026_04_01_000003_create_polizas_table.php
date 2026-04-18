<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('polizas', function (Blueprint $table) {
            $table->id();
            $table->string('numero_poliza')->unique();
            $table->string('ramo'); // Autos, GMM, Daños
            $table->foreignId('user_id')->constrained(); // Agente responsable
            $table->foreignId('asegurado_id')->constrained();
            $table->foreignId('aseguradora_id')->constrained('aseguradoras');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('prima_total', 15, 2);
            $table->string('frecuencia_pago'); // Anual, Semestral, etc.
            $table->boolean('es_flotilla')->default(false);
            $table->boolean('flotilla_existente')->default(false);
            $table->string('inciso')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('polizas');
    }
};
