<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Videoconferencia extends Model
{
    // Campos que se pueden llenar masivamente
    protected $fillable = [
    'titulo',
    'descripcion',
    'fecha',
    'link',
    'dermatologo_id',
    'imagen',
    ];


    // Relación: la videoconferencia la crea un dermatólogo (User con rol dermatólogo)
    public function dermatologo()
    {
        return $this->belongsTo(User::class, 'dermatologo_id');
    }

    // Relación: pacientes que se inscriben a la videoconferencia
    public function pacientes()
    {
        return $this->belongsToMany(User::class, 'videoconferencia_user', 'videoconferencia_id', 'user_id')
                    ->withTimestamps();
    }
}
