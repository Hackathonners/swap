<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Judite\Models\DirectExchange;
use Illuminate\Queue\SerializesModels;

class ConfirmedExchangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The confirmed exchange.
     *
     * @var string
     */
    private $exchange;

    /**
     * Create a new message instance.
     *
     * @param \App\Judite\Models\DirectExchange $exchange
     */
    public function __construct(DirectExchange $exchange)
    {
        $this->exchange = $exchange;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $course = $this->exchange->course();
        $fromStudent = $this->exchange->fromStudent();
        $toStudent = $this->exchange->toStudent();
        $fromShift = $this->exchange->fromShift();
        $toShift = $this->exchange->toShift();

        return $this->markdown('emails.exchanges.confirmed',
            compact('course', 'fromStudent', 'toStudent', 'fromShift', 'toShift'));
    }
}
