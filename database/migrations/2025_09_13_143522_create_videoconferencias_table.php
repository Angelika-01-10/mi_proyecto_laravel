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
    Schema::create('videoconferencias', function (Blueprint $table) {
    $table->id();
    $table->string('titulo');
    $table->text('descripcion')->nullable();
    $table->dateTime('fecha');
    $table->string('link'); // link a la reunión
    $table->foreignId('dermatologo_id')->constrained('users'); // quien la crea
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videoconferencias');
    }
};
