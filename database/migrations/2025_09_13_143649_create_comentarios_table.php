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
        Schema::create('comentarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('foro_id')->constrained('foros')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users');
        $table->text('contenido');
        $table->string('imagen')->nullable(); // solo pacientes podrán subir
        $table->boolean('verificado')->default(false); // si lo escribe dermatólogo = true
        $table->timestamps();
 });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comentarios');
    }
};
