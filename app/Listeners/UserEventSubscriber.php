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
     *
     * @param  \Illuminate\Auth\Events\Registered  $event
     * @return void
     */
    public function onUserRegister($event)
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        $user->confirmation_code = hash_hmac('sha256', Str::random(60), config('app.key'));
        $user->locale = config('app.locale'); //$request->getPreferredLanguage(['tr', 'en'])
        $user->save();

        try {
            $user->notify(new ConfirmationCodeNotification($user->confirmation_code));
        } catch (Exception $e) {
            Log::error($e);
        }

        /*
         * Create standart portfolio data for new user.
         */
        $user->portfolios()->create([
            'name' => Lang::get('app.portfolio.default'),
            'order' => 1,
        ]);
    }

    /**
     * Handle user login events.
     *
     * @param  \Illuminate\Auth\Events\Login  $event
     * @return void
     */
    public function onUserLogin($event)
    {
        /** @var \App\Models\User $user */
        $user = $event->user;
        $user->audits()->create([
            'logon_at' => now(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::header('User-Agent'),
        ]);
    }

    /**
     * Handle user logout events.
     *
     * @param  \Illuminate\Auth\Events\Logout  $event
     * @return void
     */
    public function onUserLogout($event)
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
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
