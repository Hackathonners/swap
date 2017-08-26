<?php

namespace App\Judite\Contracts\Registry;

use Carbon\Carbon;
use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;

interface ExchangeRegistryEntry
{
    /**
     * Get source shift of this recorded exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function fromShift(): Shift;

    /**
     * Get target shift of this recorded exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function toShift(): Shift;

    /**
     * Get source student of this recorded exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function fromStudent(): Student;

    /**
     * Get target student of this recorded exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function toStudent(): Student;

    /**
     * Get course of this recorded exchange.
     *
     * @return \App\Judite\Models\Course
     */
    public function course(): Course;

    /**
     * Get date of this recorded exchange.
     *
     * @return \Carbon\Carbon
     */
    public function getDate(): Carbon;
}
