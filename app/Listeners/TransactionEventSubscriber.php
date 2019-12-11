<?php

namespace App\Listeners;

use App\Enums\TransactionType;
use App\Models\Share;
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
        $share->average_amount_with_dividend = $share->average_amount_with_dividend->add($transaction->amount);
        $share->average = $share->average_amount->divide($share->lot);
        $share->average_with_dividend = $share->average_amount_with_dividend->divide($share->lot);

        $share->setAmount();
        $share->setGain();
        $share->setGainWithDividend();
        $share->total_purchase_amount = $share->total_purchase_amount->add($transaction->amount);
        $share->paid_amount = $share->paid_amount->add($transaction->amount);
        $share->total_commission_amount = $share->total_commission_amount->add($transaction->commission_price);
        $share->total_gain = $share->total_gain->subtract($transaction->commission_price);

        $share->update();
        $transaction->update();

        $share->portfolio->total_purchase_amount = $share->portfolio->total_purchase_amount->add($transaction->amount);
        $share->portfolio->paid_amount = $share->portfolio->paid_amount->add($transaction->amount);
        $share->portfolio->total_gain = $share->portfolio->total_gain->subtract($transaction->commission_price);
        $share->portfolio->calculateMoneyAttributes();
        $share->portfolio->update();
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
        $transaction->update();

        $share->total_sale_amount = $share->total_sale_amount->add($transaction->amount);
        $share->total_commission_amount = $share->total_commission_amount->add($transaction->commission_price);
        $share->total_gain = $share->total_gain->subtract($transaction->commission_price);
        $share->update();

        $share->portfolio->total_sale_amount = $share->portfolio->total_sale_amount->add($transaction->amount);
        $share->portfolio->total_gain = $share->portfolio->total_gain->subtract($transaction->commission_price);
        $share->portfolio->update();

        $items = $share->getTransactionsByTypeAndNotSold();
        $transactionLot = $transaction->lot;

        $items->each(function ($item) use ($transaction, &$transactionLot, $share) {
            if ($item->remaining < $transactionLot) {
                $soldLot = $item->remaining;
                $item->remaining = 0;
                $item->sale_average_amount = $item->sale_average_amount->add($transaction->price->multiply($soldLot));
                $item->sale_average = $item->sale_average_amount->divide($item->lot);
                $item->sale_gain = $item->sale_average_amount->subtract($item->amount);
                $item->update();

                $buyingAmount = $item->price->multiply($soldLot);
                if ($item->type->is(TransactionType::Bonus)) {
                    $buyingAmount = Money::TRY(0);
                }
                $soldAmount = $transaction->price->multiply($soldLot);
                $transactionGain = $soldAmount->subtract($buyingAmount);
                $transaction->sale_gain = $transaction->sale_gain->add($transactionGain);
                $transaction->update();

                $share->gain_loss = $share->gain_loss->add($transactionGain);
                $share->total_gain = $share->total_gain->add($transactionGain);
                $share->average_amount = $share->average_amount->subtract($buyingAmount);
                $share->average_amount_with_dividend = $share->average_amount_with_dividend->subtract($buyingAmount);
                $share->lot = $share->lot - $soldLot;
                $share->average = $share->average_amount->divide($share->lot);
                $share->average_with_dividend = $share->average_amount_with_dividend->divide($share->lot);
                $share->setAmount();
                $share->setGain();
                $share->setGainWithDividend();
                $share->paid_amount = $share->paid_amount->subtract($buyingAmount);
                $share->update();

                $share->portfolio->paid_amount = $share->portfolio->paid_amount->subtract($buyingAmount);
                $share->portfolio->gain_loss = $share->portfolio->gain_loss->add($transactionGain);
                $share->portfolio->total_gain = $share->portfolio->total_gain->add($transactionGain);
                $share->portfolio->update();

                $transactionLot = $transactionLot - $soldLot;
            }

            if ($item->remaining >= $transactionLot) {
                $soldLot = $transactionLot;
                $item->remaining = $item->remaining - $soldLot;
                $item->sale_average_amount = $item->sale_average_amount->add($transaction->price->multiply($soldLot));
                $itemSoldLot = (int) ($item->lot - $item->remaining);
                $item->sale_average = $item->sale_average_amount->divide($itemSoldLot);
                $item->sale_gain = $item->sale_average_amount->subtract($item->amount);
                $item->update();

                $buyingAmount = $item->price->multiply($soldLot);
                if ($item->type->is(TransactionType::Bonus)) {
                    $buyingAmount = Money::TRY(0);
                }
                $soldAmount = $transaction->price->multiply($soldLot);
                $transactionGain = $soldAmount->subtract($buyingAmount);
                $transaction->sale_gain = $transaction->sale_gain->add($transactionGain);
                $transaction->update();

                $share->gain_loss = $share->gain_loss->add($transactionGain);
                $share->total_gain = $share->total_gain->add($transactionGain);
                $share->average_amount = $share->average_amount->subtract($buyingAmount);
                $share->average_amount_with_dividend = $share->average_amount_with_dividend->subtract($buyingAmount);
                $share->lot = $share->lot - $soldLot;
                $share->average = ($share->lot == 0) ? '0' : $share->average_amount->divide($share->lot);
                $share->average_with_dividend = ($share->lot == 0) ? '0' : $share->average_amount_with_dividend->divide($share->lot);
                $share->setAmount();
                $share->setGain();
                $share->setGainWithDividend();
                $share->paid_amount = $share->paid_amount->subtract($buyingAmount);
                $share->update();

                $share->portfolio->paid_amount = $share->portfolio->paid_amount->subtract($buyingAmount);
                $share->portfolio->gain_loss = $share->portfolio->gain_loss->add($transactionGain);
                $share->portfolio->total_gain = $share->portfolio->total_gain->add($transactionGain);
                $share->portfolio->update();

                $transactionLot = 0;

                return false;
            }
        });

        $share->portfolio->calculateMoneyAttributes();
        $share->portfolio->update();
    }

    /**
     * Handle user's share for dividend transaction.
     */
    public function onShareDividendPaid($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;
        $transaction->dividend = $transaction->dividend_gain->divide($transaction->lot);
        $transaction->update();

        $share->total_dividend_gain = $share->total_dividend_gain->add($transaction->dividend_gain);
        $share->total_gain = $share->total_gain->add($transaction->dividend_gain);
        $share->average_amount_with_dividend = $share->average_amount_with_dividend->subtract($transaction->dividend_gain);
        $share->average_with_dividend = $share->average_amount_with_dividend->divide($share->lot);
        $share->setGainWithDividend();
        $share->update();

        $share->portfolio->total_gain = $share->portfolio->total_gain->add($transaction->dividend_gain);
        $share->portfolio->calculateMoneyAttributes();
        $share->portfolio->update();
    }

    /**
     * Handle user's share for giving bonus share.
     */
    public function onShareBonusPaid($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $transaction->bonus = ($transaction->lot * 100) / $share->lot;
        $transaction->remaining = $transaction->lot;
        $transaction->update();

        $share->lot += $transaction->lot;
        $share->average = $share->average_amount->divide($share->lot);
        $share->average_with_dividend = $share->average_amount_with_dividend->divide($share->lot);
        $share->total_bonus_share += $transaction->lot;
        $share->setAmount();
        $share->setGain();
        $share->setGainWithDividend();
        $share->update();

        $share->portfolio->calculateMoneyAttributes();
        $share->portfolio->update();
    }

    /**
     * Handle user's share for use rights.
     */
    public function onShareUseRights($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $transaction->rights = ($transaction->lot * 100) / $share->lot;
        $transaction->remaining = $transaction->lot;
        $transaction->price = Money::TRY(100);
        $transaction->amount = $transaction->price->multiply($transaction->lot);
        $transaction->update();

        $share->lot += $transaction->lot;
        $share->average_amount = $share->average_amount->add($transaction->amount);
        $share->average_amount_with_dividend = $share->average_amount_with_dividend->add($transaction->amount);
        $share->average = $share->average_amount->divide($share->lot);
        $share->average_with_dividend = $share->average_amount_with_dividend->divide($share->lot);


        $share->total_rights_share += $transaction->lot;
        $share->setAmount();
        $share->setGain();
        $share->setGainWithDividend();
        $share->total_purchase_amount = $share->total_purchase_amount->add($transaction->amount);
        $share->paid_amount = $share->paid_amount->add($transaction->amount);
        $share->update();

        $share->portfolio->total_purchase_amount = $share->portfolio->total_purchase_amount->add($transaction->amount);
        $share->portfolio->paid_amount = $share->portfolio->paid_amount->add($transaction->amount);
        $share->portfolio->calculateMoneyAttributes();
        $share->portfolio->update();
    }


    /**
     * Handle user's share for deleting buying transaction.
     */
    public function onShareBuyingDeleted($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $share->lot -= $transaction->lot;
        $share->average_amount = $share->average_amount->subtract($transaction->amount);
        $share->average_amount_with_dividend = $share->average_amount_with_dividend->subtract($transaction->amount);
        $share->average = ($share->lot == 0) ? '0' : $share->average_amount->divide($share->lot);
        $share->average_with_dividend = ($share->lot == 0) ? '0' : $share->average_amount_with_dividend->divide($share->lot);

        $share->setAmount();
        $share->setGain();
        $share->setGainWithDividend();
        $share->total_purchase_amount = $share->total_purchase_amount->subtract($transaction->amount);
        $share->paid_amount = $share->paid_amount->subtract($transaction->amount);
        $share->total_commission_amount = $share->total_commission_amount->subtract($transaction->commission_price);
        $share->total_gain = $share->total_gain->add($transaction->commission_price);

        $share->save();

        $share->portfolio->total_purchase_amount = $share->portfolio->total_purchase_amount->subtract($transaction->amount);
        $share->portfolio->paid_amount = $share->portfolio->paid_amount->subtract($transaction->amount);
        $share->portfolio->total_gain = $share->portfolio->total_gain->add($transaction->commission_price);
        $share->portfolio->calculateMoneyAttributes();
    }

    /**
     * Handle user's share for sale transaction.
     */
    public function onShareSaleDeleted($event)
    {
        $transaction = $event->transaction;
        $share = $transaction->share;

        $share->total_sale_amount = $share->total_sale_amount->subtract($transaction->amount);
        $share->total_commission_amount = $share->total_commission_amount->subtract($transaction->commission_price);
        $share->total_gain = $share->total_gain->add($transaction->commission_price);
        $share->update();

        $share->portfolio->total_sale_amount = $share->portfolio->total_sale_amount->subtract($transaction->amount);
        $share->portfolio->total_gain = $share->portfolio->total_gain->add($transaction->commission_price);
        $share->portfolio->update();

        $items = $share->getTransactionsByTypeAndSold();
        $transactionLot = $transaction->lot;

        $items->each(function ($item) use ($transaction, &$transactionLot, $share) {
            $itemSoldLot = $item->lot - $item->remaining;
            $soldLot = ($itemSoldLot > $transactionLot) ? $transactionLot : $itemSoldLot;
            $item->remaining = $item->remaining + $soldLot;

            $item->sale_average_amount = $item->sale_average_amount->subtract($transaction->price->multiply($soldLot));
            $itemSoldLot = (int) ($item->lot - $item->remaining);
            $item->sale_average = ($itemSoldLot == 0) ? '0' :  $item->sale_average_amount->divide($itemSoldLot);
            $item->sale_gain = ($item->sale_average_amount->equals(Money::TRY(0))) ? '0' : $item->sale_average_amount->subtract($item->amount);
            $item->update();

            $buyingAmount = $item->price->multiply($soldLot);
            if ($item->type->is(TransactionType::Bonus)) {
                $buyingAmount = Money::TRY(0);
            }
            $soldAmount = $transaction->price->multiply($soldLot);
            $transactionGain = $soldAmount->subtract($buyingAmount);
            $transaction->sale_gain = $transaction->sale_gain->subtract($transactionGain);
            $transaction->update();

            $share->gain_loss = $share->gain_loss->subtract($transactionGain);
            $share->total_gain = $share->total_gain->subtract($transactionGain);
            $share->average_amount = $share->average_amount->add($buyingAmount);
            $share->average_amount_with_dividend = $share->average_amount_with_dividend->add($buyingAmount);
            $share->lot = $share->lot + $soldLot;
            $share->average = ($share->lot == 0) ? '0' : $share->average_amount->divide($share->lot);
            $share->average_with_dividend = ($share->lot == 0) ? '0' : $share->average_amount_with_dividend->divide($share->lot);
            $share->setAmount();
            $share->setGain();
            $share->setGainWithDividend();
            $share->paid_amount = $share->paid_amount->add($buyingAmount);
            $share->update();

            $share->portfolio->paid_amount = $share->portfolio->paid_amount->add($buyingAmount);
            $share->portfolio->gain_loss = $share->portfolio->gain_loss->subtract($transactionGain);
            $share->portfolio->total_gain = $share->portfolio->total_gain->subtract($transactionGain);
            $share->portfolio->update();

            $transactionLot = $transactionLot - $soldLot;

            if ($transactionLot == 0) {
                return false;
            }
        });

        $share->portfolio->calculateMoneyAttributes();
        $share->portfolio->update();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
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
            'App\Events\BonusTransactionCreated',
            'App\Listeners\TransactionEventSubscriber@onShareBonusPaid'
        );

        $events->listen(
            'App\Events\RightsTransactionCreated',
            'App\Listeners\TransactionEventSubscriber@onShareUseRights'
        );

        $events->listen(
            'App\Events\BuyingTransactionDeleted',
            'App\Listeners\TransactionEventSubscriber@onShareBuyingDeleted'
        );

        $events->listen(
            'App\Events\SaleTransactionDeleted',
            'App\Listeners\TransactionEventSubscriber@onShareSaleDeleted'
        );
    }
}
