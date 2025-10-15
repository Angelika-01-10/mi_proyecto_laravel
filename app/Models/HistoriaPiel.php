<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoriaPiel extends Model
{
    use HasFactory;

    protected $table = 'historias_piel';

    protected $fillable = [
        'user_id',
        'dermatologo_id',
        'foto_perfil',
        'descripcion_sintomas',
        'documento_medico',
        'estado',
        'respuesta_diagnostico',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dermatologo()
    {
        return $this->belongsTo(User::class, 'dermatologo_id');
    }
}
