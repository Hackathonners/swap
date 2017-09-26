<?php

namespace App\Exceptions;

use Exception;
use App\Judite\Models\Enrollment;

class EnrollmentCannotBeDeleted extends Exception
{
    /**
     * The enrollment that cannot be deleted.
     *
     * @var \App\Judite\Models\Enrollment
     */
    protected $enrollment;

    /**
     * Create a new exception instance.
     *
     * @param \App\Judite\Models\Enrollment $course
     * @param string                        $message
     */
    public function __construct(Enrollment $enrollment = null, $message = 'The enrollment cannot be deleted.')
    {
        parent::__construct($message);
        $this->enrollment = $enrollment;
    }

    /**
     * Get the enrollment of this exception.
     *
     * @return \App\Judite\Models\Enrollment
     */
    public function getEnrollment()
    {
        return $this->enrollment;
    }
}
