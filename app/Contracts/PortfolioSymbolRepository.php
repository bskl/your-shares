<?php

namespace App\Contracts;

use App\Models\PortfolioSymbol;
use App\Contracts\BaseContract;

interface PortfolioSymbolRepository extends BaseRepository
{
    /**
     * Create a new portfolio symbol for the given data.
     *
     * @param  array  $data
     * @return App\Models\PortfolioSymbol  $portfolioSymbol
     */
    public function create(array $data);

    /**
     * Update the portfolio symbol with given data.
     *
     * @param  App\Models\PortfolioSymbol  $portfolioSymbol
     * @param  array   $data
     * @return App\Models\PortfolioSymbol  $portfolioSymbol
     */
    public function update(PortfolioSymbol $portfolioSymbol, array $data);

    /**
     * Delete the portfolio symbol.
     *
     * @param  App\Models\PortfolioSymbol  $portfolioSymbol
     * @return void
     */
    public function delete(PortfolioSymbol $portfolioSymbol);
}