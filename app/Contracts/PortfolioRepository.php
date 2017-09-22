<?php

namespace App\Contracts;

use App\Models\Portfolio;
use App\Contracts\BaseContract;

interface PortfolioRepository extends BaseRepository
{
    /**
     * Create a new portfolio for the given data.
     *
     * @param  array  $data
     * @return App\Models\Portfolio  $portfolio
     */
    public function create(array $data);

    /**
     * Create standart portfolio data for given user.
     *
     * @param  integer  $userId
     * @return App\Models\Portfolio  $portfolio
     */
    public function createDefaultPortfolio(integer $userId);

    /**
     * Update the portfolio with given data.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @param  array   $data
     * @return App\Models\Portfolio  $portfolio
     */
    public function update(array $data, Portfolio $portfolio);

    /**
     * Delete the portfolio.
     *
     * @param  App\Models\Portfolio  $portfolio
     * @return void
     */
    public function delete(Portfolio $portfolio);

    /**
     * @return App\Models\Portfolio  $portfolio
	 */
	public function getWithSymbols();

    /**
     * Get the number of portfolios for auth user.
     *
     * @return int
     */
    public function count();

    /**
     * Attach symbol to given user's portfolio.
     *
     * @param  array  $data
     * @return void
     */
     public function attachSymbolToPortfolio(array $data);
}