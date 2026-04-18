<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('poliza_gmm', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poliza_id')->constrained()->onDelete('cascade');
            $table->string('endoso')->nullable();
            $table->decimal('suma_asegurada', 15, 2)->nullable();
            $table->decimal('deducible', 15, 2)->nullable();
            $table->decimal('coaseguro', 5, 2)->nullable();
            $table->string('plan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('poliza_gmm');
    }
};
