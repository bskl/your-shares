<?php

namespace App\Listeners;

use App\Events\SymbolUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateShareAmount
{
    /**
     * Handle the event.
     *
     * @param  BuyingTransactionCreated  $event
     * @return void
     */
    public function handle(SymbolUpdated $event)
    {
        $symbol = $event->symbol;

        $shares = Share::where('code', $symbol->code)->get();

        $shares->map(function ($share) use ($symbol) {
            $share->calculateTotalAmount($symbol->last_price);
            $share->update();
        });
    }
}
