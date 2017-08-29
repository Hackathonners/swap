<?php

if (! function_exists('student')) {
    /**
     * Get the authenticated student.
     *
     * @return \App\Judite\Models\Student
     */
    function student()
    {
        return Auth::user()->student;
    }
}
