<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PortfolioPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the portfolio.
     *
     * @param \App\Models\User      $user
     * @param \App\Models\Portfolio $portfolio
     *
     * @return mixed
     */
    public function view(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->user_id;
    }

    /**
     * Determine whether the user can create portfolios.
     *
     * @param \App\Models\User $user
     *
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the portfolio.
     *
     * @param \App\Models\User      $user
     * @param \App\Models\Portfolio $portfolio
     *
     * @return mixed
     */
    public function update(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->user_id;
    }

    /**
     * Determine whether the user can delete the portfolio.
     *
     * @param \App\Models\User      $user
     * @param \App\Models\Portfolio $portfolio
     *
     * @return mixed
     */
    public function delete(User $user, Portfolio $portfolio)
    {
        return $user->id === $portfolio->user_id;
    }
}
