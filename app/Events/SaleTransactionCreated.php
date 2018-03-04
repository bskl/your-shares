<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Queue\SerializesModels;

class SaleTransactionCreated
{
    use SerializesModels;

    public $transaction;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }
}
