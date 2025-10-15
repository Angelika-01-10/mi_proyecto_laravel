<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('perfiles_dermatologos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('especialidad')->nullable();
            $table->string('titulo_profesional')->nullable();
            $table->string('numero_licencia')->nullable();
            $table->text('biografia')->nullable();
            $table->string('foto_perfil')->nullable();
            $table->string('curriculum')->nullable(); // PDF del CV
            $table->integer('años_experiencia')->nullable();
            $table->string('telefono')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('perfiles_dermatologos');
    }
};