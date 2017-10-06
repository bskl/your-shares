<?php

namespace App\Repositories;

use App\Models\Portfolio;
use App\Contracts\PortfolioRepository;
use App\Repositories\EloquentBaseRespository;
use Illuminate\Support\Facades\Lang;

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
     * @return App\Models\Portfolio  $portfolio
     */
    public function create(array $data)
    {
        $this->model->create($data);
    }

    /**
     * Create standart portfolio data for given user.
     *
     * @param  integer  $userId
     * @return App\Models\Portfolio  $portfolio
     */
    public function createDefaultPortfolio(int $userId)
    {
        $this->create([
            'user_id' => $userId,
            'name' => Lang::get('app.portfolio.default'),
            'order' => 1,
        ]);
    }

    /**
     * Update the portfolio with given data.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @param  array   $data
     * @return App\Models\Portfolio  $portfolio
     */
    public function update(Portfolio $portfolio, array $data)
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
}