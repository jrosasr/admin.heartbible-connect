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
        Schema::create('story_series', function (Blueprint $table) {
            $table->id(); // ID por defecto para Filament y para evitar problemas de claves compuestas
            $table->foreignId('story_id')->constrained()->onDelete('cascade');
            $table->foreignId('serie_id')->constrained()->onDelete('cascade'); // Asegúrate de que sea 'serie_id'
            $table->timestamps();

            // Opcional: Asegurar que una historia no se pueda añadir dos veces a la misma serie
            $table->unique(['story_id', 'serie_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('story_series');
    }
};