<?php

namespace App\Listeners;

class TransactionEventSubscriber
{    
    /**
     * Handle user register events.
     */
    public function onSharePurchased($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $transaction->amount = $transaction->price->multiply($transaction->lot);
        $transaction->commission_price = $transaction->amount->multiply($transaction->commission)
                                                             ->divide(100);

        $share->lot += $transaction->lot;
        $share->average_amount = $share->average_amount->add($transaction->amount);
        $share->average = $share->average_amount->divide($share->lot);
        $share->calculateGain();

        $share->save();
        $transaction->save();
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
            'App\Events\BuyingTransactionCreated',
            'App\Listeners\TransactionEventSubscriber@onSharePurchased'
        );
    }

}
