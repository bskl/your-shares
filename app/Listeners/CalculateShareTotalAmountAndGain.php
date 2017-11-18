<?php

namespace App\Listeners;

use App\Models\Share;
use App\Events\SymbolUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateShareTotalAmountAndGain
{
    /**
     * calculate shares money attributes with symbol's last_price.
     *
     * @param  SymbolUpdated  $event
     * @return void
     */
    public function handle(SymbolUpdated $event)
    {
        $symbol = $event->symbol;

        $shares = Share::where('symbol_id', $symbol->id)->get();

        $shares->map(function ($share) use ($symbol) {
            $share->calculateTotalAmount($symbol->last_price);
            $share->calculateGain();
            $share->update();
        });
    }
}
