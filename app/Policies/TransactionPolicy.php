<?php

namespace App\Policies;

use App\Models\Share;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the share.
     *
     * @param \App\Models\User        $user
     * @param \App\Models\Transaction $transaction
     *
     * @return mixed
     */
    public function view(User $user, Transaction $transaction)
    {
        return $user->id === $share->portfolio->user_id;
    }

    /**
     * Determine whether the user can create shares.
     *
     * @param \App\Models\User        $user
     * @param \App\Models\Transaction $transaction
     * @param \App\Models\Share       $share
     *
     * @return mixed
     */
    public function create(User $user, Share $share)
    {
        return $user->id === $share->user_id;
    }

    /**
     * Determine if the given post can be deleted by the user.
     *
     * @param \App\Modals\User        $user
     * @param \App\Models\Transaction $transaction
     *
     * @return bool
     */
    public function delete(User $user, Transaction $transaction)
    {
        return $user->id === $transaction->user_id;
    }
}
