<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Share;
use App\Models\Transaction;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the share.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $share->portfolio->user_id;
    }

    /**
     * Determine whether the user can create shares.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Transaction  $transaction
     * @param  \App\Models\Share  $share
     * @return mixed
     */
    public function create(User $user, Share $share)
    {
        return $user->id === $share->user_id;
    }
}
