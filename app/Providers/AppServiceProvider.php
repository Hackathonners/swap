<?php

namespace App\Providers;

use App\Judite\Models\Settings;
use Illuminate\Support\ServiceProvider;
use App\Judite\Contracts\ExchangeLogger;
use Illuminate\Support\Facades\Validator;
use App\Judite\Logger\EloquentExchangeLogger;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('student_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(a|pg)[0-9]+$/', $value) === 1;
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ExchangeLogger::class, function ($app) {
            return new EloquentExchangeLogger;
        });

        $this->app->singleton('settings', function ($app) {
            return Settings::firstOrNew([]);
        });
    }
}
