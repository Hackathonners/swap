<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CannotExchangeEnrollmentMultipleTimesException;
use App\Exceptions\CannotExchangeToShiftsOnDifferentCoursesException;

class Exchange extends Model
{
    /**
     * Set the enrollments of this exchange.
     *
     * @param  \App\Judite\Models\Enrollemnt  $fromEnrollment
     * @param  \App\Judite\Models\Enrollemnt  $toEnrollment
     * @return $this
     */
    public function setExchangeEnrollments(Enrollment $fromEnrollment, Enrollment $toEnrollment)
    {
        // Each enrollment can be requested to exchange once. The students
        // are allowed to create only a single exchange related to the
        // same enrollment, ensuring that each request exists once.
        if ($fromEnrollment->exchanges()->where('confirmed', false)->exists()) {
            throw new CannotExchangeEnrollmentMultipleTimesException;
        }

        if ($fromEnrollment->course_id !== $toEnrollment->course_id) {
            throw new CannotExchangeToShiftsOnDifferentCoursesException;
        }

        $this->fromEnrollment()->associate($fromEnrollment);
        $this->toEnrollment()->associate($toEnrollment);

        return $this;
    }

    /**
     * Check if an inverse exchange exists.
     *
     * @return \App\Judite\Models\Exchange
     */
    public static function findMatchingExchange(Enrollment $fromEnrollment, Enrollment $toEnrollment)
    {
        $inverseMatch = [
            'from_enrollment_id' => $toEnrollment->id,
            'to_enrollment_id' => $fromEnrollment->id,
        ];

        return self::where($inverseMatch)->first();
    }

    /**
     * Perform the exchange and update the enrollments of involved students.
     *
     * @return $this
     */
    public function perform()
    {
        $fromEnrollment = $this->fromEnrollment;
        $toEnrollment = $this->toEnrollment;

        $fromEnrollment->exchange($toEnrollment);

        // If the exchange is going to be performed, then we must update
        // the confirmation status to confirmed so the history of the
        // exchanges is kept and include this enrollment exchange.
        $this->confirmed = true;
        $this->save();

        // Delete the unconfirmed exchanges related to the source enrollment.
        $fromEnrollment->exchanges()->where('confirmed', false)->delete();

        return $this;
    }

    /**
     * Get the source enrollment of this exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fromEnrollment()
    {
        return $this->belongsTo(Enrollment::class, 'from_enrollment_id');
    }

    /**
     * Get the target enrollment of this exchange.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function toEnrollment()
    {
        return $this->belongsTo(Enrollment::class, 'to_enrollment_id');
    }
}
