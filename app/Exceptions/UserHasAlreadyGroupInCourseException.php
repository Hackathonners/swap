<?php

namespace App\Exceptions;

use Exception;
use App\Judite\Models\Course;

class UserHasAlreadyGroupInCourseException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param \App\Judite\Models\Course $course
     * @param string                    $message
     */
    public function __construct($message = 'User has already a group in course.')
    {
        parent::__construct($message);
    }
}
