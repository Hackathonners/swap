<?php

namespace App\Exceptions;

use Exception;

class CannotExchangeEnrollmentWithoutAssociatedShiftException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Cannot exchange an enrollment that does not have an associated shift.')
    {
        parent::__construct($message);
    }
}
