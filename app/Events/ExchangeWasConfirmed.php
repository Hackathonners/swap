<?php

namespace App\Events;

use App\Judite\Models\Exchange;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\Channel;

class ExchangeWasConfirmed implements ShouldBroadcast
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

    /**
     * Channel to send the notification
     */
    public function broadcastOn()
    {
        return new Channel('exchange');
    }
}
