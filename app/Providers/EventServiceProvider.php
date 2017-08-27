<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\ExchangeWasConfirmed' => [
            'App\Listeners\SendConfirmedExchangeNotification',
        ],
        'App\Events\ExchangeWasDeclined' => [
            'App\Listeners\SendDeclinedExchangeNotification',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
