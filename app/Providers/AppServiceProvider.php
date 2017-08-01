<?php

namespace App\Providers;

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
        Validator::extend('student_email', function ($attribute, $value, $parameters, $validator) {
            return preg_match("/^(a|pg)[0-9]+@alunos\.uminho\.pt$/", $value) === 1;
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
    }
}
