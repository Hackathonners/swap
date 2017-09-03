<?php

namespace App\Providers;

use App\Judite\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use App\Judite\Registry\EloquentExchangeRegistry;
use App\Judite\Contracts\Registry\ExchangeRegistry;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        Auth::macro('student', function () {
            return Auth::check() ? Auth::user()->student : null;
        });

        Validator::extend('student_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(a|pg)[0-9]+$/', $value) === 1;
        });
    }

    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->singleton(ExchangeRegistry::class, function ($app) {
            return new EloquentExchangeRegistry();
        });

        $this->app->singleton('settings', function ($app) {
            return Settings::firstOrNew([]);
        });
    }
}
