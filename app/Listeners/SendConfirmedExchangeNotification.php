<?php

namespace App\Listeners;

use App\Events\ExchangeWasConfirmed;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmedExchangeNotification;

class SendConfirmedExchangeNotification
{
    /**
     * Handle the event.
     *
     * @param ExchangeWasConfirmed $event
     */
    public function handle(ExchangeWasConfirmed $event)
    {
        Mail::to($event->exchange->fromStudent()->user)
            ->send(new ConfirmedExchangeNotification($event->exchange));
    }
}
