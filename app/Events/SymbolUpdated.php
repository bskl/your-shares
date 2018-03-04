<?php

namespace App\Events;

use App\Models\Symbol;
use Illuminate\Queue\SerializesModels;

class SymbolUpdated
{
    use SerializesModels;

    public $symbol;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     *
     * @return void
     */
    public function __construct(Symbol $symbol)
    {
        $this->symbol = $symbol;
    }
}
