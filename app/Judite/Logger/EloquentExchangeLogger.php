<?php

namespace App\Judite\Logger;

use App\Judite\Models\Enrollment;
use App\Judite\Models\LogExchange;
use App\Judite\Contracts\ExchangeLogger;

class EloquentExchangeLogger implements ExchangeLogger
{
    /**
     * {@inheritdoc}
     */
    public function log(Enrollment $fromEnrollment, Enrollment $toEnrollment)
    {
        $logExchange = LogExchange::make();
        $logExchange->fromShift()->associate($fromEnrollment->shift);
        $logExchange->toShift()->associate($toEnrollment->shift);
        $logExchange->fromStudent()->associate($fromEnrollment->student);
        $logExchange->toStudent()->associate($toEnrollment->student);
        $logExchange->save();
    }
}
