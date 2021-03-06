<?php

namespace App\Observers;

use App\Models\Share;
use Illuminate\Support\Facades\Auth;

class ShareObserver
{
    /**
     * Handle the share "creating" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function creating(Share $share)
    {
        $share->user_id = Auth::id();
    }

    /**
     * Handle the share "created" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function created(Share $share)
    {
        //
    }

    /**
     * Handle the share "updated" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function updated(Share $share)
    {
        //
    }

    /**
     * Handle the share "deleted" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function deleted(Share $share)
    {
        //
    }

    /**
     * Handle the share "restored" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function restored(Share $share)
    {
        //
    }

    /**
     * Handle the share "force deleted" event.
     *
     * @param  \App\Models\Share  $share
     * @return void
     */
    public function forceDeleted(Share $share)
    {
        //
    }
}
