<?php

namespace App\Observers;

use App\Models\Portfolio;

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
        //
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
