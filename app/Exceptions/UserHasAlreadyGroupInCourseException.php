<?php

namespace App\Exceptions;

use Exception;
use App\Judite\Models\Course;

class UserHasAlreadyGroupInCourseException extends Exception
{
    /**
     * Course where User has already a Group.
     *
     * @var \App\Judite\Models\Course
     */
    protected $course;

    /**
     * Create a new exception instance.
     *
     * @param \App\Judite\Models\Course $course
     * @param string                    $message
     */
    public function __construct(Course $course = null, $message = 'User already has a group in course.')
    {
        parent::__construct($message);
        $this->course = $course;
    }

    /**
     * Get the course of this exception.
     *
     * @return \App\Judite\Models\Course
     */
    public function getCourse()
    {
        return $this->course;
    }
}
