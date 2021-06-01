<?php

namespace App\Providers;

use App\Rules\MoneyGt;
use App\Traits\MoneyManager;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use InvalidArgumentException;

class AppServiceProvider extends ServiceProvider
{
    use MoneyManager;

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
        Blade::if('env', function ($environment) {
            return app()->environment($environment);
        });

        Validator::extend('money_gt', function ($attribute, $value, $parameters, $validator) {
            if (count($parameters) < 1) {
                throw new InvalidArgumentException('Validation rule money_gt requires at least 1 parameters.');
            }

            $rule = new MoneyGt($parameters[0]);

            return $rule->passes($attribute, $value);
        });

        Validator::replacer('money_gt', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':value', $this->formatByIntl($this->parseByDecimal($parameters[0])), $message);
        });
    }
}
