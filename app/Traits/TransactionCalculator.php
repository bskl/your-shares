<?php

namespace App\Traits;

use App\Models\Portfolio;
use App\Models\Share;
use App\Models\Transaction;
use Money\Money;

trait TransactionCalculator
{
    /**
     * Handle calculations when create a new buying transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleBuyingCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $transaction->handleBuyingCalculations();
        $share->handleBuyingCalculations($transaction);
        $portfolio->handleBuyingCalculations($transaction);
    }

    /**
     * Handle calculations when delete a buying transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedBuyingCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $share->handleDeletedBuyingCalculations($transaction);
        $portfolio->handleDeletedBuyingCalculations($transaction);
    }

    /**
     * Handle calculations when create a new sale transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleSaleCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $items = $share->getTransactionsByTypeAndNotSold();
        $lot = $transaction->lot;
        $gain = $amount = Money::TRY(0);

        $items->each(function ($item) use ($transaction, $share, &$lot, &$gain, &$amount) {
            $soldLot = ($item->remaining < $lot) ? $item->remaining : $lot;
            $soldAmount = $transaction->price->multiply($soldLot);
            $item->remaining = $item->remaining - $soldLot;
            $item->sale_average_amount = $item->sale_average_amount->add($soldAmount);
            $item->sale_average = $item->sale_average_amount->divide($item->lot - $item->remaining);
            $item->sale_gain = $item->sale_average_amount->subtract($item->amount);
            $item->update();

            $buyingAmount = $item->price->multiply($soldLot);
            $amount = $amount->add($buyingAmount);
            $gain = $gain->add($soldAmount->subtract($buyingAmount));

            $lot = $lot - $soldLot;
            if ($lot == 0) {
                return false;
            }
        });

        $transaction->handleSaleCalculations($gain);
        $share->handleSaleCalculations($transaction, $gain, $amount);
        $portfolio->handleSaleCalculations($transaction, $gain, $amount);
    }

    /**
     * Handle calculations when delete a sale transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedSaleCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $items = $share->getTransactionsByTypeAndSold();
        $lot = $transaction->lot;
        $gain = $amount = Money::TRY(0);

        $items->each(function ($item) use ($transaction, $share, &$lot, &$gain, &$amount) {
            $itemSold = $item->lot - $item->remaining;
            $soldLot = ($itemSold < $lot) ? $itemSold : $lot;
            $item->remaining = $item->remaining + $soldLot;
            $soldAmount = $transaction->price->multiply($soldLot);
            $item->sale_average_amount = $item->sale_average_amount->subtract($soldAmount);
            $itemPreSold = (int) ($item->lot - $item->remaining);
            $item->sale_average = ($itemPreSold == 0) ? '0' : $item->sale_average_amount->divide($itemPreSold);
            $item->sale_gain = ($item->sale_average_amount->equals(Money::TRY(0))) ? '0' : $item->sale_average_amount->subtract($item->amount);
            $item->update();

            $buyingAmount = $item->price->multiply($soldLot);
            $amount = $amount->add($buyingAmount);
            $gain = $gain->add($soldAmount->subtract($buyingAmount));

            $lot = $lot - $soldLot;
            if ($lot == 0) {
                return false;
            }
        });

        $share->handleDeletedSaleCalculations($transaction, $gain, $amount);
        $portfolio->handleDeletedSaleCalculations($transaction, $gain, $amount);
    }

    /**
     * Handle calculations when create a new dividend transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDividendCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $transaction->dividend = $transaction->dividend_gain->divide($transaction->lot);
        $transaction->update();
        $share->handleDividendCalculations($transaction);
        $portfolio->handleDividendCalculations($transaction);
    }

    /**
     * Handle calculations when delete a dividend transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedDividendCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $share->handleDeletedDividendCalculations($transaction);
        $portfolio->handleDeletedDividendCalculations($transaction);
    }

    /**
     * Handle calculations when create a new bonus transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleBonusCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $transaction->handleBonusCalculations($share);
        $share->handleBonusCalculations($transaction);
        $portfolio->handleBonusCalculations($transaction);
    }

    /**
     * Handle calculations when delete a bonus transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedBonusCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $share->handleDeletedBonusCalculations($transaction);
        $portfolio->handleDeletedBonusCalculations($transaction);
    }

    /**
     * Handle calculations when create a new rights transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleRightsCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $transaction->handleRightsCalculations($share);
        $share->handleRightsCalculations($transaction);
        $portfolio->handleRightsCalculations($transaction);
    }

    /**
     * Handle calculations when delete a rights transaction instance.
     *
     * @param  \App\Models\Portfolio $portfolio
     * @param  \App\Models\Share $share
     * @param  \App\Models\Transaction $transaction
     *
     * @return void
     */
    public function handleDeletedRightsCalculations(Portfolio $portfolio, Share $share, Transaction $transaction)
    {
        $share->handleDeletedRightsCalculations($transaction);
        $portfolio->handleDeletedRightsCalculations($transaction);
    }
}
