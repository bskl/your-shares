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
     * @param \App\Models\Symbol $symbol
     *
     * @return void
     */
    public function __construct(Symbol $symbol)
    {
        $this->symbol = $symbol;
    }
}
