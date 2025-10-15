<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class VideoconferenciaInicioNotification extends Notification
{
    use Queueable;

    protected $videoconferencia;

    public function __construct($videoconferencia)
    {
        $this->videoconferencia = $videoconferencia;
    }

    // 👉 Guardamos la notificación en BD en vez de enviar email
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'titulo' => $this->videoconferencia->titulo,
            'fecha' => $this->videoconferencia->fecha,
            'link' => $this->videoconferencia->link,
        ];
    }
}
