<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('skin_analyses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // usuario que hizo el análisis
            $table->string('image_path'); // ruta de la imagen
            $table->string('skin_type'); // tipo de piel detectado
            $table->json('probabilities')->nullable(); // probabilidades en JSON
            $table->timestamps();

            // Relación con tabla users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('skin_analyses');
    }
};
