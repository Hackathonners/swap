<?php

namespace App\Listeners;

use App\Events\ExchangeWasDeclined;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeclinedExchangeNotification;

class SendDeclinedExchangeNotification
{
    /**
     * Handle the event.
     *
     * @param ExchangeWasDeclined $event
     */
    public function handle(ExchangeWasDeclined $event)
    {
        Mail::to($event->exchange->fromStudent()->user)
            ->send(new DeclinedExchangeNotification($event->exchange));
    }
}
