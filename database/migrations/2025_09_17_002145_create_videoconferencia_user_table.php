<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('videoconferencia_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('videoconferencia_id')->constrained('videoconferencias')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['videoconferencia_id', 'user_id']); // evita duplicados
        });
    }

   public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Tu videoconferencia está por iniciar')
        ->line('La videoconferencia "' . $this->videoconferencia->titulo . '" está a punto de comenzar.')
        ->action('Unirme ahora', $this->videoconferencia->link)
        ->line('¡Te esperamos!');
}

};
