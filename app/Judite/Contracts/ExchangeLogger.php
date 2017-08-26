<?php

namespace App\Judite\Contracts;

use App\Judite\Models\Enrollment;

interface ExchangeLogger
{
    /**
     * Log an exchange of enrollments to the exchanges history.
     *
     * @param \App\Judite\Models\Enrollment $fromEnrollment
     * @param \App\Judite\Models\Enrollment $toEnrollment
     */
    public function log(Enrollment $fromEnrollment, Enrollment $toEnrollment);

    /**
     * Get the exchanges history.
     * 
     * @return \Illuminate\Contracts\Pagination
     */
    public function history();
}
