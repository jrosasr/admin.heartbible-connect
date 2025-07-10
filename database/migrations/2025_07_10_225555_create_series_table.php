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
        Schema::create('series', function (Blueprint $table) {
            $table->id(); // ID por defecto de Laravel
            $table->string('title')->unique(); // Título de la serie, único
            $table->text('description')->nullable(); // Descripción de la serie, puede ser nula
            $table->enum('difficulty', ['low', 'medium', 'high', 'legend'])->default('low');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};