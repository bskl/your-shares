<?php

namespace App\Policies;

use App\Models\Symbol;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SymbolPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any symbols.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the symbol.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Symbol  $symbol
     * @return mixed
     */
    public function view(User $user, Symbol $symbol)
    {
        //
    }

    /**
     * Determine whether the user can create symbols.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the symbol.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the symbol.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Symbol  $symbol
     * @return mixed
     */
    public function delete(User $user, Symbol $symbol)
    {
        //
    }

    /**
     * Determine whether the user can restore the symbol.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Symbol  $symbol
     * @return mixed
     */
    public function restore(User $user, Symbol $symbol)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the symbol.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Symbol  $symbol
     * @return mixed
     */
    public function forceDelete(User $user, Symbol $symbol)
    {
        //
    }
}
