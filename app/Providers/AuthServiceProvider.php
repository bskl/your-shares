<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Portfolio'   => 'App\Policies\PortfolioPolicy',
        'App\Models\Share'       => 'App\Policies\SharePolicy',
        'App\Models\Symbol'      => 'App\Policies\SymbolPolicy',
        'App\Models\Transaction' => 'App\Policies\TransactionPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerGates();
    }

    /**
     * Define gates.
     *
     * @return void
     */
    public function registerGates()
    {
        Gate::define('is_admin', function ($user) {
            return $user->isAdmin();
        });
    }
}
