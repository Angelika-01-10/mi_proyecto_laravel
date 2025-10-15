<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historias_piel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Paciente
            $table->foreignId('dermatologo_id')->nullable()->constrained('users')->onDelete('set null'); // Dermatólogo
            $table->string('foto_perfil')->nullable();
            $table->text('descripcion_sintomas');
            $table->string('documento_medico')->nullable();
            $table->enum('estado', ['pendiente', 'en_revision', 'cerrada'])->default('pendiente');
            $table->text('respuesta_diagnostico')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historias_piel');
    }
};
