<?php

namespace App\Providers;

use App\Judite\Models\Settings;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
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
        if (App::environment('production')) {
            URL::forceScheme('https');
        }

        Auth::macro('student', function () {
            return Auth::check() ? Auth::user()->student : null;
        });

        Validator::extend('student_number', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^(a|pg)[0-9]+$/i', $value) === 1;
        });

        Validator::extend('greater_than_field', function($attribute, $value, $parameters, $validator) {
            $min_field = $parameters[0];
            $data = $validator->getData();
            $min_value = $data[$min_field];
            return $value > $min_value;
        });

        Validator::replacer('greater_than_field', function($message, $attribute, $rule, $parameters) {
            return str_replace(':field', $parameters[0], $message);
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
