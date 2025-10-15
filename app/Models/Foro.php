<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Foro extends Model
{
    // Agregamos 'imagen' al fillable
    protected $fillable = ['titulo', 'contenido', 'user_id', 'imagen'];

    // Relación: un foro pertenece a un usuario (quien lo creó)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relación: un foro tiene muchos comentarios
    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }
}
