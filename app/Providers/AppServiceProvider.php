<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //\Horizon::auth(function ($request) {
            //dd(auth()->user());
        //});
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
