<?php

namespace App\Contracts;

use App\Models\Symbol;
use App\Models\Portfolio;

interface SymbolRepository extends BaseRepository
{
    /**
     * Add the symbol on th given portfolio.
     *
     * @param  App\Models\Symbol     $symbol
     * @return App\Models\Portfolio  $portfolio
     */
    public function addSymbolToPortfolio(Symbol $symbol, Portfolio $portfolio);
}