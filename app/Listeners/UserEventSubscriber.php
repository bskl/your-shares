<?php

namespace App\Listeners;

use App\Models\Portfolio;
use Illuminate\Support\Facades\Lang;

class UserEventSubscriber
{    
    /**
     * Handle user register events.
     */
    public function onUserRegister($event)
    {
        /**
         * Create standart portfolio data for new user.
         */
        Portfolio::create([
            'user_id' => $event->user->id,
            'name' => Lang::get('app.portfolio.default'),
            'order' => 1,
        ]);
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($event) {
        
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event) {
        
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  Dispatcher  $events
     */
    public function subscribe($events) {
        $events->listen(
            'Illuminate\Auth\Events\Registered',
            'App\Listeners\UserEventSubscriber@onUserRegister'
        );
        $events->listen(
            'Illuminate\Auth\Events\Login',
            'App\Listeners\UserEventSubscriber@onUserLogin'
        );

        $events->listen(
            'Illuminate\Auth\Events\Logout',
            'App\Listeners\UserEventSubscriber@onUserLogout'
        );
    }

}
