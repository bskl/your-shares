<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Contracts\PortfolioRepository',
            'App\Repositories\EloquentPortfolioRepository'
        );

        $this->app->bind(
            'App\Contracts\SymbolRepository',
            'App\Repositories\EloquentSymbolRepository'
        );
    }
}
