<?php

namespace App\Events;

use App\Judite\Models\DirectExchange;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ExchangeWasDeclined
{
    use Dispatchable, SerializesModels;

    /**
     * The declined exchange.
     *
     * @var \App\Judite\Models\DirectExchange
     */
    public $exchange;

    /**
     * Create a new event instance.
     */
    public function __construct(DirectExchange $exchange)
    {
        $this->exchange = $exchange;
    }
}
