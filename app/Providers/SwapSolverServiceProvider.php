<?php

namespace App\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use App\Judite\Services\SwapSolverService;

class SwapSolverServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SwapSolverService::class, function ($app) {
            $uri = $this->app['config']->get('services.swapsolver.endpoint');
            $client = new Client(['base_uri' => $uri]);
            return new SwapSolverService($client);
        });
    }
}
