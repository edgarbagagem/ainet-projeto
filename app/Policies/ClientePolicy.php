<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Cliente;

class ClientePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */

    public function viewAny(User $user)
    {
        return $user->tipo == 'A';
    }

    public function view(User $user, Cliente $cliente)
    {
        return ($user->id == $cliente->id);
    }


    public function create(User $user)
    {
        //
    }

    public function update(User $user, Cliente $cliente)
    {
        return $user->id == $cliente->id || $user->tipo = 'A';
    }

    public function delete(User $user)
    {
        return $user->tipo == 'A';
    }

    public function restore(User $user, Cliente $cliente)
    {
        //
    }

    public function forceDelete(User $user, Cliente $cliente)
    {
        //
    }
}
