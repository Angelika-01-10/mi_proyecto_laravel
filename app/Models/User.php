<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relaciones existentes (basadas en tu base de datos)
    public function foros()
    {
        return $this->hasMany(Foro::class);
    }

    public function comentarios()
    {
        return $this->hasMany(Comentario::class);
    }

    public function videoconferenciasCreadas()
    {
        return $this->hasMany(Videoconferencia::class, 'dermatologo_id');
    }

    public function videoconferenciasInscritas()
    {
        return $this->belongsToMany(Videoconferencia::class, 'videoconferencia_user');
    }

    // Nuevas relaciones para el chat dermatológico
    public function chatConversaciones()
    {
        return $this->hasMany(ChatConversacion::class);
    }

    public function chatMensajes()
    {
        return $this->hasManyThrough(ChatMensaje::class, ChatConversacion::class);
    }

    // Métodos de utilidad
    public function esAdministrador()
    {
        return $this->hasRole('administrador');
    }

    public function esDermatologo()
    {
        return $this->hasRole('dermatologo');
    }

    public function esPaciente()
    {
        return $this->hasRole('paciente');
    }

    // En App\Models\User.php

public function perfilDermatologo()
{
    return $this->hasOne(PerfilDermatologo::class);
}

// app/Models/User.php
public function isAdmin()
{
    return strtolower($this->rol) === 'administrador';
}

}