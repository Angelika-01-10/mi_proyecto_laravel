<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    protected $fillable = [
        'foro_id',
        'user_id',
        'contenido',
        'imagen',
        'verificado'
    ];

    // Relación: comentario hecho por un usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: comentario pertenece a un foro
    public function foro()
    {
        return $this->belongsTo(Foro::class);
    }

    // 👇 Útil para saber rápido si lo escribió un dermatólogo
    public function getEsVerificadoAttribute()
    {
        return $this->verificado ? '✅ Verificado por dermatólogo' : 'Comentario de paciente';
    }
}
