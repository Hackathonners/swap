<?php

namespace App\Events;

use App\Judite\Models\Exchange;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ExchangeWasConfirmed
{
    use Dispatchable, SerializesModels;

    /**
     * The confirmed exchange.
     *
     * @var \App\Judite\Models\Exchange
     */
    public $exchange;

    /**
     * Create a new event instance.
     */
    public function __construct(Exchange $exchange)
    {
        $this->exchange = $exchange;
    }
}
