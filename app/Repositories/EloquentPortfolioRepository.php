<?php

namespace App\Repositories;

use App\Models\Portfolio;
use App\Contracts\PortfolioRepository;
use App\Repositories\EloquentBaseRespository;

class EloquentPortfolioRepository extends EloquentBaseRepository implements PortfolioRepository
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
    public function __construct(Portfolio $portfolio)
    {
        $this->model = $portfolio;
    }

    /**
     * Create a new portfolio for the given data.
     *
     * @param  array  $data
     * @return int
     */
    public function create(array $data)
    {
        return $this->model->byCurrentUser()->create($data);
    }

    /**
     * Update the portfolio with given data.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @param  array   $data
     * @return App\Models\Portfolio  $portfolio
     */
    public function update(array $data, Portfolio $portfolio)
    {
        return $portfolio->update($data);
    }

    /**
     * Delete the portfolio.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @return void
     */
    public function delete(Portfolio $portfolio)
    {
        return $portfolio->delete();
    }

    /**
     * Get the number of portfolios for auth user.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->byCurrentUser()->count();
    }

    /**
     * Attach symbol to given user's portfolio.
     *
     * @param  array  $data
     * @return void
     */
    public function attachSymbolToPortfolio(array $data)
    {
        $portfolio = $this->model->findOrFail($data['portfolio_id']);
        
        $portfolio->symbols()->attach($data['symbol_id']);

        $portfolioSymbol = $portfolio->symbols()->findOrFail($data['symbol_id']);

        return $portfolioSymbol;
    }
}