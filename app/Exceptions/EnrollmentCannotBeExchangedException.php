<?php

namespace App\Exceptions;

use Exception;

class EnrollmentCannotBeExchangedException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string $message
     */
    public function __construct($message = 'The enrollment cannot be exchanged.')
    {
        parent::__construct($message);
    }
}
