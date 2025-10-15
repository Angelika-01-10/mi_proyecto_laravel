<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerfilDermatologo extends Model
{
    protected $table = 'perfiles_dermatologos';
    
    protected $fillable = [
        'user_id',
        'especialidad',
        'titulo_profesional',
        'numero_licencia',
        'biografia',
        'foto_perfil',
        'curriculum',
        'años_experiencia',
        'telefono'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}