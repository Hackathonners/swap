<?php

namespace App\Judite\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\CannotExchangeToShiftsOnDifferentCoursesException;

class Enrollment extends Model
{
    /**
     * Get student of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get exchanges of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchanges()
    {
        return $this->hasMany(Exchange::class);
    }

    /**
     * Get course of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get shift of this enrollment.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    /**
     * Set exchanges from this enrollment to a given collection of shifts.
     *
     * @param  \Illuminate\Support\Collection  $shifts
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function setExchanges(Collection $shifts)
    {
        // Remove the current existing exchanges from this enrollment.
        $this->exchanges()->delete();

        // We are going to create an exchange for each target shift given as
        // parameter. An exception will be thrown if a shift is associated
        // to a course distinct from the one of the current enrollment.
        $exchanges = $shifts->map(function ($shift) {
            if ($shift->course_id !== $this->course_id) {
                throw new CannotExchangeToShiftsOnDifferentCoursesException;
            }

            $exchange = $this->exchanges()->make();
            $exchange->shift()->associate($shift);

            return $exchange;
        });

        return $this->exchanges()->saveMany($exchanges);
    }
}
