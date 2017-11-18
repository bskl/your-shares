<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        \App\Events\SymbolUpdated::class => [
            \App\Listeners\CalculateShareTotalAmountAndGain::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
     protected $subscribe = [
        \App\Listeners\UserEventSubscriber::class,
        \App\Listeners\TransactionEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
