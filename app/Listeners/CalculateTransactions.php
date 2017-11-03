<?php

namespace App\Listeners;

class CalculateTransactions
{    
    /**
     * Handle user register events.
     */
    public function buyingShare($event)
    {
        $transaction = $event->transaction;
        
        $share = $event->transaction->share;

        $transaction->amount = $transaction->price->multiply($transaction->lot);

        $transaction->commission_price = $transaction->amount->multiply($transaction->commission)->divide(100);

        $share->lot = $share->lot + $transaction->lot;

        $share->average_amount = $share->average_amount->add($transaction->amount);

        $share->average = $share->average_amount->divide($share->lot);

        $share->calculateGain();

        $share->save();
    }

    /**
     * Handle user login events.
     */
    public function saleShare($event) {
        
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events) {
        $events->listen(
                'App\Events\BuyingTransaction',
                    'App\Listeners\CalculateTransactions@buyingShare'
        );
    }

}
