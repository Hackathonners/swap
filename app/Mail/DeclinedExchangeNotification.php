<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Judite\Models\Exchange;
use Illuminate\Queue\SerializesModels;

class DeclinedExchangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The declined exchange.
     *
     * @var string
     */
    private $exchange;

    /**
     * Create a new message instance.
     *
     * @param \App\Judite\Models\Exchange $exchange
     */
    public function __construct(Exchange $exchange)
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

        return $this->markdown('emails.exchanges.declined',
            compact('course', 'fromStudent', 'toStudent', 'fromShift', 'toShift'));
    }
}
