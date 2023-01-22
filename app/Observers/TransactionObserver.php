<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;

class TransactionObserver
{
    /**
     * Handle the transaction "creating" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function creating(Transaction $transaction): void
    {
        $transaction->user_id = Auth::id();
    }

    /**
     * Handle the transaction "created" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function created(Transaction $transaction): void
    {
        $method = 'handleCalculationsOf'.$transaction->type->name;
        TransactionService::{$method}($transaction);
    }

    /**
     * Handle the transaction "updated" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function updated(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the transaction "deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function deleted(Transaction $transaction): void
    {
        $method = 'handleCalculationsOfDeleted'.$transaction->type->name;
        TransactionService::{$method}($transaction);
    }

    /**
     * Handle the transaction "restored" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the transaction "force deleted" event.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
