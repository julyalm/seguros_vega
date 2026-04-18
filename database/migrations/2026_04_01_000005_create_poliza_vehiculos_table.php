<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poliza_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poliza_id')->constrained()->onDelete('cascade');
            $table->string('tipo');
            $table->integer('modelo');
            $table->string('marca');
            $table->string('submarca');
            $table->string('vin', 17)->nullable();
            $table->string('motor')->nullable();
            $table->string('placas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_vehiculos');
    }
};
