<?php

namespace App\Exceptions;

use Exception;

class CannotExchangeToShiftsOnDifferentCoursesException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'Cannot exchange to shifts on different courses.')
    {
        parent::__construct($message);
    }
}
