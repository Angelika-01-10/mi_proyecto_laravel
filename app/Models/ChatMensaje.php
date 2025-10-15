<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMensaje extends Model
{
    use HasFactory;

    protected $table = 'chat_mensajes';
    
    protected $fillable = [
        'conversacion_id',
        'user_id', // identifica quién envió el mensaje
        'tipo',     // 'usuario' o 'asistente'
        'contenido',
        'imagenes'
    ];

    protected $casts = [
        'imagenes' => 'array',
    ];

    /**
     * Conversación a la que pertenece el mensaje
     */
    public function conversacion(): BelongsTo
    {
        return $this->belongsTo(ChatConversacion::class, 'conversacion_id');
    }

    /**
     * Usuario que envió el mensaje
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
