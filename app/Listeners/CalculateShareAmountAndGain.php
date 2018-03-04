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
        $symbol = $event->symbol;

        $shares = Share::where('symbol_id', $symbol->id)->get();

        $shares->map(function ($share) use ($symbol) {
            $share->calculateAmount($symbol->last_price);
            $share->calculateGain();
            $share->update();
            $share->portfolio->calculateMoneyAttributes();
        });
    }
}
