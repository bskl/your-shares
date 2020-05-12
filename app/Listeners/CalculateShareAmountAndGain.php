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
        $shares = Share::where('symbol_id', $event->symbol->id)->get();

        $shares->map(function ($share) {
            $share->handleCommonCalculations();
        });
    }
}
