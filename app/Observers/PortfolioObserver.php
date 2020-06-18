<?php

namespace App\Observers;

use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;

class PortfolioObserver
{
    /**
     * Handle the portfolio "creating" event.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return void
     */
    public function creating(Portfolio $portfolio)
    {
        $portfolio->user_id = Auth::id();
        $portfolio->order = Portfolio::byCurrentUser()->count() + 1;
    }

    /**
     * Handle the portfolio "created" event.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return void
     */
    public function created(Portfolio $portfolio)
    {
        //
    }

    /**
     * Handle the portfolio "updated" event.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return void
     */
    public function updated(Portfolio $portfolio)
    {
        //
    }

    /**
     * Handle the portfolio "deleted" event.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return void
     */
    public function deleted(Portfolio $portfolio)
    {
        //
    }

    /**
     * Handle the portfolio "restored" event.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return void
     */
    public function restored(Portfolio $portfolio)
    {
        //
    }

    /**
     * Handle the portfolio "force deleted" event.
     *
     * @param  \App\Models\Portfolio  $portfolio
     * @return void
     */
    public function forceDeleted(Portfolio $portfolio)
    {
        //
    }
}
