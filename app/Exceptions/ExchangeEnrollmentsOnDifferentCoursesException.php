<?php

namespace App\Exceptions;

use Exception;

class ExchangeEnrollmentsOnDifferentCoursesException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct($message = 'Cannot exchange to shifts on different courses.')
    {
        parent::__construct($message);
    }
}
