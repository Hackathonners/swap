<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $view->with('settings', app('settings'));
        });
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
        //
    }
}
