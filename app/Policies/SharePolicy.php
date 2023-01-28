<?php

namespace App\Policies;

use App\Models\Share;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SharePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can list the shares.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the share.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return mixed
     */
    public function view(User $user, Share $share)
    {
        return $user->id === $share->user_id;
    }

    /**
     * Determine whether the user can create shares.
     *
     * @param  \App\Models\User  $user
     * @param  int  $portfolioId
     * @return mixed
     */
    public function create(User $user, int $portfolioId)
    {
        return $user->portfolios()->where('id', $portfolioId)->exists();
    }

    /**
     * Determine whether the user can update the share.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return mixed
     */
    public function update(User $user, Share $share)
    {
        return $user->id === $share->user_id;
    }

    /**
     * Determine whether the user can delete the share.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Share  $share
     * @return mixed
     */
    public function delete(User $user, Share $share)
    {
        return $user->id === $share->user_id;
    }
}
