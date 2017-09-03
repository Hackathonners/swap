<?php

namespace App\Judite\Contracts\Registry;

use App\Judite\Models\Enrollment;
use App\Judite\Models\Student;

interface ExchangeRegistry
{
    /**
     * Record an exchange of enrollments in the exchanges history.
     *
     * @param \App\Judite\Models\Enrollment $fromEnrollment
     * @param \App\Judite\Models\Enrollment $toEnrollment
     */
    public function record(Enrollment $fromEnrollment, Enrollment $toEnrollment);

    /**
     * Get the exchanges history paginator.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate();

    /**
     * Truncate registry.
     */
    public function truncate();

    /**
     * Get the exchanges history paginator of a given student.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function historyOfStudent(Student $student);
}
