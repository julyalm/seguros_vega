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
        Schema::create('asegurado_direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asegurado_id')->constrained()->onDelete('cascade');
            $table->string('codigo_postal', 5);
            $table->string('estado');
            $table->string('municipio');
            $table->string('colonia');
            $table->string('calle');
            $table->string('num_exterior');
            $table->string('num_interior')->nullable();
            $table->string('alias')->nullable(); // Casa, Oficina, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asegurado_direcciones');
    }
};
