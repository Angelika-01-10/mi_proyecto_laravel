<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatConversacion extends Model
{
    use HasFactory;

    protected $table = 'chat_conversaciones';
    
    protected $fillable = [
        'user_id',
        'titulo',
    ];

    /**
     * Usuario que creó la conversación
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Mensajes de la conversación
     */
    public function mensajes(): HasMany
    {
        return $this->hasMany(ChatMensaje::class, 'conversacion_id');
    }

    /**
     * Último mensaje de la conversación
     */
    public function ultimoMensaje(): HasOne
    {
        return $this->hasOne(ChatMensaje::class, 'conversacion_id')->latest();
    }
}
