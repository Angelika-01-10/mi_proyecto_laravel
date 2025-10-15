<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comentario;
use Illuminate\Auth\Access\HandlesAuthorization;

class ComentarioPolicy
{
    use HandlesAuthorization;

    /**
     * Determinar si el usuario puede actualizar el comentario
     */
    public function update(User $user, Comentario $comentario)
    {
        // Puede editar si es el autor o un dermatólogo
        return $user->id === $comentario->user_id || $user->hasRole('dermatologo');
    }

    /**
     * Determinar si el usuario puede eliminar el comentario
     */
    public function delete(User $user, Comentario $comentario)
    {
        // Puede eliminar si es el autor o un dermatólogo
        return $user->id === $comentario->user_id || $user->hasRole('dermatologo');
    }
}
