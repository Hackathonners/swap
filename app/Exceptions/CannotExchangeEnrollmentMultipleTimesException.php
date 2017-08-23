<?php

namespace App\Exceptions;

use Exception;

class CannotExchangeEnrollmentMultipleTimesException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct($message = 'Cannot exchange an enrollment multiple times.')
    {
        parent::__construct($message);
    }
}
