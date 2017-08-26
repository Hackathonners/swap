<?php

namespace App\Judite\Contracts\Registry;

interface ExchangeRegistryEntry
{
    /**
     * Get source shift of this recorded exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function fromShift();

    /**
     * Get target shift of this recorded exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function toShift();

    /**
     * Get source student of this recorded exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function fromStudent();

    /**
     * Get target student of this recorded exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function toStudent();

    /**
     * Get course of this recorded exchange.
     *
     * @return \App\Judite\Models\Course
     */
    public function course();
}
