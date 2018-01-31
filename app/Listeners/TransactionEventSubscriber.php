<?php

namespace App\Listeners;

use App\Models\Share;
use App\Enums\TransactionTypes;
use Money\Money;

class TransactionEventSubscriber
{    
    /**
     * Handle user's share for buying transaction.
     */
    public function onSharePurchased($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $transaction->remaining = $transaction->lot;
        $transaction->amount = $transaction->price->multiply($transaction->lot);
        $transaction->commission_price = $transaction->amount->multiply($transaction->commission)
                                                             ->divide(100);

        $share->lot += $transaction->lot;
        $share->average_amount = $share->average_amount->add($transaction->amount);
        $share->average = $share->average_amount->divide($share->lot);

        $share->calculateAmount($share->symbol->last_price);
        $share->calculateGain();
        $share->total_average_amount = $share->total_average_amount->add($transaction->amount);
        $share->total_commission_amount = $share->total_commission_amount->add($transaction->commission_price);
        $share->total_gain = $share->total_gain->subtract($transaction->commission_price);

        $share->save();
        $transaction->save();

        $share->portfolio->total_average_amount = $share->portfolio->total_average_amount->add($transaction->amount);
        $share->portfolio->calculateMoneyAttributes();
    }

    /**
     * Handle user's share for sale transaction.
     */
    public function onShareSold($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $transaction->amount = $transaction->price->multiply($transaction->lot);
        $transaction->commission_price = $transaction->amount->multiply($transaction->commission)
                                                             ->divide(100);
        $transaction->save();

        $share->total_sale_amount = $share->total_sale_amount->add($transaction->amount);
        $share->total_commission_amount = $share->total_commission_amount->add($transaction->commission_price);
        $share->total_gain = $share->total_gain->subtract($transaction->commission_price);
        $share->save();

        $items = $share->getBuyingTransactionsByNotSold();

        $items->each(function ($item) use ($transaction, $share) {
            if ($item->remaining < $transaction->lot) {
                $soldLot = $item->remaining;
                $item->remaining = 0;
                $item->sale_average_amount = $item->sale_average_amount->add($transaction->price->multiply($soldLot));
                $item->sale_average = $item->sale_average_amount->divide($item->lot);
                $item->sale_gain = $item->sale_average_amount->subtract($item->amount);
                $item->save();

                $share->lot = $share->lot - $soldLot;
                $buyingAmount = $item->price->multiply($soldLot);
                $soldAmount = $transaction->price->multiply($soldLot);
                $share->average_amount = $share->average_amount->subtract($buyingAmount);
                $share->average = $share->average_amount->divide($share->lot);
                $share->calculateAmount($share->symbol->last_price);
                $share->calculateGain();
                $transactionGain = $soldAmount->subtract($buyingAmount);
                $share->total_gain = $share->total_gain->add($transactionGain);
                $share->save();
                
                $transaction->lot = $transaction->lot - $soldLot;
            }

            if ($item->remaining >= $transaction->lot) {
                $item->remaining = $item->remaining - $transaction->lot;
                $item->sale_average_amount = $item->sale_average_amount->add($transaction->price->multiply($transaction->lot));
                $soldLot = (int) ($item->lot - $item->remaining);
                $item->sale_average = $item->sale_average_amount->divide($soldLot);
                $item->sale_gain = $item->sale_average_amount->subtract($item->amount);
                $item->save();

                $buyingAmount = $item->price->multiply($transaction->lot);
                if ($item->type == TransactionTypes::BONUSISSUE) {
                    $buyingAmount = Money::TRY(0);
                }
                $soldAmount = $transaction->price->multiply($transaction->lot);
                $transactionGain = $soldAmount->subtract($buyingAmount);
                $share->total_gain = $share->total_gain->add($transactionGain);

                $share->lot = $share->lot - $transaction->lot;
                if ($share->lot == 0) {
                    $share->average_amount = $share->average = $share->amount = $share->gain = '0';
                } else {
                    $share->average_amount = $share->average_amount->subtract($buyingAmount);
                    $share->average = $share->average_amount->divide($share->lot);
                    $share->calculateAmount($share->symbol->last_price);
                    $share->calculateGain();
                }
                $share->save();

                $transaction->lot = 0;
                return false;
            }
        });

        $share->portfolio->total_sale_amount = $share->portfolio->total_sale_amount->add($transaction->amount);
        $share->portfolio->calculateMoneyAttributes();
    }

    /**
     * Handle user's share for dividend transaction.
     */
    public function onShareDividendPaid($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;
        $transaction->dividend = $transaction->dividend_gain->divide($transaction->lot);
        $transaction->save();

        $share->total_dividend_gain = $share->total_dividend_gain->add($transaction->dividend_gain);
        $share->total_gain = $share->total_gain->add($transaction->dividend_gain);
        $share->save();

        $share->portfolio->calculateMoneyAttributes();
    }

    /**
     * Handle user's share for giving bonus issue.
     */
    public function onShareBonusIssue($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $transaction->bonus_issue = ($transaction->lot * 100) / $share->lot;
        $transaction->remaining = $transaction->lot;
        $transaction->save();

        $share->lot += $transaction->lot;
        $share->calculateAmount($share->symbol->last_price);
        $share->calculateGain();
        $share->average = $share->average_amount->divide($share->lot);
        $share->total_bonus_issue_share += $transaction->lot;
        $share->save();

        $share->portfolio->calculateMoneyAttributes();
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

        $events->listen(
            'App\Events\SaleTransactionCreated',
            'App\Listeners\TransactionEventSubscriber@onShareSold'
        );

        $events->listen(
            'App\Events\DividendTransactionCreated',
            'App\Listeners\TransactionEventSubscriber@onShareDividendPaid'
        );

        $events->listen(
            'App\Events\BonusIssueTransactionCreated',
            'App\Listeners\TransactionEventSubscriber@onShareBonusIssue'
        );
    }

}
