<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asegurados', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rfc', 13)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->string('genero')->nullable();
            $table->string('direccion')->nullable();
            $table->string('email')->nullable();
            $table->string('telefono', 15)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asegurados');
    }
};
