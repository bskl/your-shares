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
     * Determine whether the user can list the transactions.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the transaction.
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
     * Determine whether the user can create transactions.
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
     * Determine whether the user can delete the transaction.
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
