<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Videoconferencia;

class VideoconferenciaPolicy
{
    /**
     * Ver listado de videoconferencias.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['administrador', 'dermatologo', 'paciente']);
    }

    /**
     * Ver una videoconferencia.
     */
    public function view(User $user, Videoconferencia $videoconferencia): bool
    {
        return $user->hasRole(['administrador', 'dermatologo', 'paciente']);
    }

    /**
     * Crear videoconferencias (solo dermatólogo o administrador).
     */
    public function create(User $user): bool
    {
        return $user->hasRole(['administrador', 'dermatologo']);
    }

    /**
     * Editar videoconferencias (solo dermatólogo propietario o administrador).
     */
    public function update(User $user, Videoconferencia $videoconferencia): bool
    {
        return $user->hasRole('administrador') 
            || ($user->hasRole('dermatologo') && $user->id === $videoconferencia->dermatologo_id);
    }

    /**
     * Eliminar videoconferencias (solo dermatólogo propietario o administrador).
     */
    public function delete(User $user, Videoconferencia $videoconferencia): bool
    {
        return $user->hasRole('administrador') 
            || ($user->hasRole('dermatologo') && $user->id === $videoconferencia->dermatologo_id);
    }

    /**
     * Restaurar videoconferencia (solo administrador).
     */
    public function restore(User $user, Videoconferencia $videoconferencia): bool
    {
        return $user->hasRole('administrador');
    }

    /**
     * Eliminar permanentemente (solo administrador).
     */
    public function forceDelete(User $user, Videoconferencia $videoconferencia): bool
    {
        return $user->hasRole('administrador');
    }
}
