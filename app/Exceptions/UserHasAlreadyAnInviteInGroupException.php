<?php

namespace App\Exceptions;

use Exception;

class UserHasAlreadyAnInviteInGroupException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param \App\Judite\Models\Course $course
     * @param string                    $message
     */
    public function __construct($message = 'User already invited.')
    {
        parent::__construct($message);
    }
}
