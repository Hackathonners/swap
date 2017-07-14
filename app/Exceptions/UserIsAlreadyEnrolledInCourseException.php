<?php

namespace App\Exceptions;

use Exception;

class UserIsAlreadyEnrolledInCourseException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param  string  $message
     * @return void
     */
    public function __construct($message = 'User is already enrolled in course.')
    {
        parent::__construct($message);
    }
}
