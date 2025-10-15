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
        // Tabla para las conversaciones del chat
        Schema::create('chat_conversaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['user_id', 'created_at']);
        });

        // Tabla para los mensajes individuales
        Schema::create('chat_mensajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversacion_id')->constrained('chat_conversaciones')->onDelete('cascade');
            $table->enum('tipo', ['usuario', 'asistente']);
            $table->text('contenido');
            $table->json('imagenes')->nullable(); // Para almacenar rutas de imágenes
            $table->timestamps();
            
            // Índices para optimizar consultas
            $table->index(['conversacion_id', 'created_at']);
            $table->index('tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_mensajes');
        Schema::dropIfExists('chat_conversaciones');
    }
};