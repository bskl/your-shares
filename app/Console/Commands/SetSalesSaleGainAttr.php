<?php

namespace App\Console\Commands;

use App\Enums\TransactionTypes;
use App\Models\Transaction;
use Illuminate\Console\Command;
use Money\Money;

class SetSalesSaleGainAttr extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'yourshares:set-sales-gain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set symbols with xml file from web service.';

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $_buyingTransactions = Transaction::whereIn('type', [TransactionTypes::BUYING, TransactionTypes::BONUS])->orderBy('date_at')->get();
        $saleTransactions = Transaction::whereIn('type', [TransactionTypes::SALE])->orderBy('date_at', 'asc')->get();

        $buyingTransactions = $_buyingTransactions->map(function (&$_buyingTransaction) {
            $_buyingTransaction->remaining = (int) $_buyingTransaction->lot;

            return $_buyingTransaction;
        });

        foreach ($saleTransactions as $saleTransaction) {
            $salebuyingTransactions = $buyingTransactions->where('share_id', $saleTransaction->share_id)->where('remaining', '!=', 0);
            $saleTransactionLot = $saleTransaction->lot;

            foreach ($salebuyingTransactions as $salebuyingTransaction) {
                if ($salebuyingTransaction->remaining < $saleTransactionLot) {
                    $soldLot = $salebuyingTransaction->remaining;
                    $salebuyingTransaction->remaining = 0;

                    $buyingAmount = $salebuyingTransaction->price->multiply($soldLot);
                    if ($salebuyingTransaction->type == TransactionTypes::BONUS) {
                        $buyingAmount = Money::TRY(0);
                    }
                    $soldAmount = $saleTransaction->price->multiply($soldLot);
                    $transactionGain = $soldAmount->subtract($buyingAmount);
                    $saleTransaction->sale_gain = $saleTransaction->sale_gain->add($transactionGain);
                    $saleTransaction->update();

                    $saleTransactionLot = $saleTransactionLot - $soldLot;
                }

                if ($salebuyingTransaction->remaining >= $saleTransactionLot) {
                    $soldLot = $saleTransactionLot;
                    $salebuyingTransaction->remaining = $salebuyingTransaction->remaining - $soldLot;

                    $buyingAmount = $salebuyingTransaction->price->multiply($soldLot);
                    if ($salebuyingTransaction->type == TransactionTypes::BONUS) {
                        $buyingAmount = Money::TRY(0);
                    }
                    $soldAmount = $saleTransaction->price->multiply($soldLot);
                    $transactionGain = $soldAmount->subtract($buyingAmount);
                    $saleTransaction->sale_gain = $saleTransaction->sale_gain->add($transactionGain);
                    $saleTransaction->update();

                    $saleTransactionLot = 0;

                    return false;
                }
            }
        }
    }
}
