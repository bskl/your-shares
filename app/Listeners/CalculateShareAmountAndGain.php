<?php

namespace App\Listeners;

use App\Events\SymbolUpdated;
use App\Models\Share;

class CalculateShareAmountAndGain
{
    /**
     * calculate shares money attributes with symbol's last_price.
     *
     * @param SymbolUpdated $event
     *
     * @return void
     */
    public function handle(SymbolUpdated $event)
    {
        Share::whereSymbolId($event->symbol->id)
             ->get()
             ->each(function ($share) {
                 $share->handleCommonCalculations();
             });
    }
}
