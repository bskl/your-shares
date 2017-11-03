<?php

namespace App\Listeners;

use App\Events\BuyingTransactionCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateSymbolAverageAmount
{
    /**
     * Handle the event.
     *
     * @param  BuyingTransactionCreated  $event
     * @return void
     */
    public function handle(BuyingTransactionCreated $event)
    {
        $transaction = $event->transaction;

        $share = $event->transaction->share;

        $transaction->amount = $transaction->price->multiply($transaction->lot);

        $transaction->commission_price = $transaction->amount->multiply($transaction->commission)->divide(100);

        $share->lot = $share->lot + $transaction->lot;

        $share->average_amount = $share->average_amount->add($transaction->amount);

        $share->average = $share->average_amount->divide($share->lot);

        $share->calculateGain();

        $share->update();
    }
}
