<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Foro;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForoPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede actualizar el foro.
     */
    public function update(User $user, Foro $foro)
    {
        // El dueño del foro, un dermatólogo o un administrador puede editar
        return $user->id === $foro->user_id
            || $user->hasRole('dermatologo')
            || $user->hasRole('admin');
    }

    /**
     * Determina si el usuario puede eliminar el foro.
     */
    public function delete(User $user, Foro $foro)
    {
        // El dueño del foro, un dermatólogo o un administrador puede eliminar
        return $user->id === $foro->user_id
            || $user->hasRole('dermatologo')
            || $user->hasRole('admin');
    }
}
