<?php

namespace App\Observers;

use App\Models\Share;
use App\Models\Symbol;

class SymbolObserver
{
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
            ->get()
            ->each(function ($share) {
                $share->handleCommonCalculations();
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
