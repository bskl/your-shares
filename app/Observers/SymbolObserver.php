<?php

namespace App\Observers;

use App\Models\Portfolio;
use App\Models\Share;
use App\Models\Symbol;
use App\Traits\MoneyManager;

class SymbolObserver
{
    use MoneyManager;

    /**
     * Handle the symbol "creating" event.
     *
     * @param \App\Models\Symbol $symbol
     * @return void
     */
    public function creating(Symbol $symbol)
    {
        //
    }

    /**
     * Handle the Symbol "created" event.
     *
     * @param  \App\Models\Symbol  $symbol
     * @return void
     */
    public function created(Symbol $symbol)
    {
        //
    }

    /**
     * Handle the Symbol "updated" event.
     *
     * @param  \App\Models\Symbol  $symbol
     * @return void
     */
    public function updated(Symbol $symbol)
    {
        Share::whereSymbolId($symbol->id)
            ->chunkById(100, function ($shares) {
                foreach ($shares as $share) {
                    $share->handleCommonCalculations();
                }
            });

        Portfolio::chunkById(100, function ($portfolios) {
            foreach ($portfolios as $portfolio) {
                $sharesGain = $this->createMoney();

                foreach ($portfolio->shares as $share) {
                    $sharesGain = $sharesGain->add($share->gain);
                }

                $portfolio->instant_gain = $sharesGain->add($portfolio->total_gain);
                $portfolio->update();
            }
        });
    }

    /**
     * Handle the Symbol "deleted" event.
     *
     * @param  \App\Models\Symbol  $symbol
     * @return void
     */
    public function deleted(Symbol $symbol)
    {
        //
    }

    /**
     * Handle the Symbol "restored" event.
     *
     * @param  \App\Models\Symbol  $symbol
     * @return void
     */
    public function restored(Symbol $symbol)
    {
        //
    }

    /**
     * Handle the Symbol "force deleted" event.
     *
     * @param  \App\Models\Symbol  $symbol
     * @return void
     */
    public function forceDeleted(Symbol $symbol)
    {
        //
    }
}
