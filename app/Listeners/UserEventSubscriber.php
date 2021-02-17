<?php

namespace App\Listeners;

use App\Notifications\ConfirmationCode as ConfirmationCodeNotification;
use Exception;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class UserEventSubscriber
{
    /**
     * Handle user register events.
     */
    public function onUserRegister($event)
    {
        $event->user->confirmation_code = hash_hmac('sha256', Str::random(60), config('app.key'));
        $event->user->locale = config('app.locale'); //$request->getPreferredLanguage(['tr', 'en'])
        $event->user->save();

        try {
            $event->user->notify(new ConfirmationCodeNotification($event->user->confirmation_code));
        } catch (Exception $e) {
            Log::error($e);
        }

        /*
         * Create standart portfolio data for new user.
         */
        $event->user->portfolios()->create([
            'name' => Lang::get('app.portfolio.default'),
            'order' => 1,
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
