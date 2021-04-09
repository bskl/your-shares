<?php

namespace App\Services;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Money\Money;

class TransactionService
{
    /**
     * Handle calculations when create a new buying transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfBuying(Transaction $transaction): void
    {
        $transaction->handleCalculationsOfBuying();
        $transaction->share->handleCalculationsOfBuying($transaction);
        $transaction->share->portfolio->handleCalculationsOfBuying($transaction);
    }

    /**
     * Handle calculations when delete a buying transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDeletedBuying(Transaction $transaction): void
    {
        $transaction->share->handleCalculationsOfDeletedBuying($transaction);
        $transaction->share->portfolio->handleCalculationsOfDeletedBuying($transaction);
    }

    /**
     * Handle calculations when create a new public offering transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfPublicOffering(Transaction $transaction): void
    {
        $transaction->handleCalculationsOfBuying();
        $transaction->share->handleCalculationsOfBuying($transaction);
        $transaction->share->portfolio->handleCalculationsOfBuying($transaction);
    }

    /**
     * Handle calculations when delete a public offering transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDeletedPublicOffering(Transaction $transaction): void
    {
        $transaction->share->handleCalculationsOfDeletedBuying($transaction);
        $transaction->share->portfolio->handleCalculationsOfDeletedBuying($transaction);
    }

    /**
     * Handle calculations when create a new sale transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfSale(Transaction $transaction): void
    {
        $items = $transaction->share->getNotSoldTransactions();
        $lot = $transaction->lot;
        $gain = $amount = Money::TRY(0);

        $items->each(function ($item) use ($transaction, &$lot, &$gain, &$amount) {
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

        $transaction->handleCalculationsOfSale($gain);
        $transaction->share->handleCalculationsOfSale($transaction, $gain, $amount);
        $transaction->share->portfolio->handleCalculationsOfSale($transaction, $gain, $amount);
    }

    /**
     * Handle calculations when delete a sale transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDeletedSale(Transaction $transaction): void
    {
        $items = $transaction->share->getSoldTransactions();
        $lot = $transaction->lot;
        $gain = $amount = Money::TRY(0);

        $items->each(function ($item) use ($transaction, &$lot, &$gain, &$amount) {
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

        $transaction->share->handleCalculationsOfDeletedSale($transaction, $gain, $amount);
        $transaction->share->portfolio->handleCalculationsOfDeletedSale($transaction, $gain, $amount);
    }

    /**
     * Handle calculations when create a new dividend transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDividend(Transaction $transaction): void
    {
        $transaction->dividend = $transaction->dividend_gain->divide($transaction->lot);
        $transaction->update();
        $transaction->share->handleCalculationsOfDividend($transaction);
        $transaction->share->portfolio->handleCalculationsOfDividend($transaction);
    }

    /**
     * Handle calculations when delete a dividend transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDeletedDividend(Transaction $transaction): void
    {
        $transaction->share->handleCalculationsOfDeletedDividend($transaction);
        $transaction->share->portfolio->handleCalculationsOfDeletedDividend($transaction);
    }

    /**
     * Handle calculations when create a new bonus transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfBonus(Transaction $transaction): void
    {
        $transaction->handleCalculationsOfBonus();
        $transaction->share->handleCalculationsOfBonus($transaction);
        $transaction->share->portfolio->handleCalculationsOfBonus($transaction);
    }

    /**
     * Handle calculations when delete a bonus transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDeletedBonus(Transaction $transaction): void
    {
        $transaction->share->handleCalculationsOfDeletedBonus($transaction);
        $transaction->share->portfolio->handleCalculationsOfDeletedBonus($transaction);
    }

    /**
     * Handle calculations when create a new rights transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfRights(Transaction $transaction): void
    {
        $transaction->handleCalculationsOfRights();
        $transaction->share->handleCalculationsOfRights($transaction);
        $transaction->share->portfolio->handleCalculationsOfRights($transaction);
    }

    /**
     * Handle calculations when delete a rights transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfDeletedRights(Transaction $transaction): void
    {
        $transaction->share->handleCalculationsOfDeletedRights($transaction);
        $transaction->share->portfolio->handleCalculationsOfDeletedRights($transaction);
    }

    /**
     * Handle calculations when create a new merger out transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfMergerOut(Transaction $transaction): void
    {
        $shareAmount = $transaction->share->average_amount;
        $transaction->share->handleCalculationsOfMergerOut($transaction);
        $newShare = $transaction->share->portfolio->handleCalculationsOfMergerOut($transaction);

        $newTransaction = new Transaction();
        $newTransaction->fill([
            'share_id' => $newShare->id,
            'type' => TransactionType::MergerIn,
            'date_at' => $transaction->date_at,
            'lot' => ($newLot = $transaction->lot * $transaction->exchange_ratio),
            'exchange_ratio' => $transaction->exchange_ratio,
        ]);
        $newTransaction->price = $shareAmount->divide($newLot);
        $newTransaction->symbol_code = $transaction->share->symbol_id;
        $newTransaction->save();
    }

    /**
     * Handle calculations when create a new merger in transaction instance.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return void
     */
    public static function handleCalculationsOfMergerIn(Transaction $transaction): void
    {
        $transaction->handleCalculationsOfMergerIn();
        $transaction->share->handleCalculationsOfMergerIn($transaction);
        $transaction->share->portfolio->handleCalculationsOfMergerIn($transaction);
    }
}
