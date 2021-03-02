<?php

namespace App\Providers;

use App\Models\Portfolio;
use App\Models\Share;
use App\Models\Symbol;
use App\Models\Transaction;
use App\Observers\PortfolioObserver;
use App\Observers\ShareObserver;
use App\Observers\SymbolObserver;
use App\Observers\TransactionObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        \App\Listeners\UserEventSubscriber::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Portfolio::observe(PortfolioObserver::class);
        Share::observe(ShareObserver::class);
        Symbol::observe(SymbolObserver::class);
        Transaction::observe(TransactionObserver::class);
    }
}
