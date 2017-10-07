<?php

namespace App\Repositories;

use App\Models\PortfolioSymbol;
use App\Contracts\PortfolioSymbolRepository;
use App\Repositories\EloquentBaseRespository;

class EloquentPortfolioSymbolRepository extends EloquentBaseRepository implements PortfolioSymbolRepository
{
    /**
     * The portfolio instance.
     */
    protected $model;

    /**
     * Create a new repository instance.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @return void
     */
    public function __construct(PortfolioSymbol $portfolioSymbol)
    {
        $this->model = $portfolioSymbol;
    }

    /**
     * Create a new portfolio symbol for the given data.
     *
     * @param  array  $data
     * @return App\Models\PortfolioSymbol  $portfolioSymbol
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Update the portfolio with given data.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @param  array   $data
     * @return App\Models\Portfolio  $portfolio
     */
    public function update(PortfolioSymbol $portfolioSymbol, array $data)
    {
        return $portfolioSymbol->update($data);
    }

    /**
     * Delete the portfolio.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @return void
     */
    public function delete(PortfolioSymbol $portfolioSymbol)
    {
        return $portfolioSymbol->delete();
    }
}