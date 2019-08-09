<?php

namespace App\Providers;

use App\Enums\UserType;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

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

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addDays(1));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(10));
    }

    /**
     * Define gates.
     *
     * @return void
     */
    public function registerGates()
    {
        Gate::define('is_admin', function ($user) {
            return $user->role->is(UserType::Admin);
        });
    }
}
