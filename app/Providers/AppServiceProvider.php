<?php

namespace App\Providers;

use App\Models\Portfolio;
use App\Models\Share;
use App\Models\Transaction;
use App\Observers\PortfolioObserver;
use App\Observers\ShareObserver;
use App\Observers\TransactionObserver;
use Carbon\Carbon;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path().'/Support/helpers.php';
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        setlocale(LC_ALL, 'tr_TR.UTF-8');
        Carbon::setLocale(app()->getLocale());

        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        Portfolio::observe(PortfolioObserver::class);
        Share::observe(ShareObserver::class);
        Transaction::observe(TransactionObserver::class);
    }
}
