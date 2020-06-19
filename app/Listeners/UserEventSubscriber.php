<?php

namespace App\Listeners;

use App\Models\Portfolio;
use App\Notifications\ConfirmationCode as ConfirmationCodeNotification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Request;

class UserEventSubscriber
{
    /**
     * Handle user register events.
     */
    public function onUserRegister($event)
    {
        $event->user->confirmation_code = hash_hmac('sha256', Str::random(60), config('app.key'));
        $event->user->save();

        $event->user->notify(new ConfirmationCodeNotification($event->user->confirmation_code));

        /*
         * Create standart portfolio data for new user.
         */
        Portfolio::create([
            'name' => Lang::get('app.portfolio.default'),
        ]);
    }

    /**
     * Handle user login events.
     */
    public function onUserLogin($event)
    {
        /*
         * Create audit for user's logon information.
         */
        $event->user->audits()->create([
            'logon_at'   => now(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }

    /**
     * Handle user logout events.
     */
    public function onUserLogout($event)
    {
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param Dispatcher $events
     */
    public function subscribe($events)
    {
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
