<?php

namespace App\Judite\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Judite\Contracts\ExchangeLogger;
use App\Exceptions\CannotExchangeEnrollmentMultipleTimesException;
use App\Exceptions\CannotExchangeToShiftsOnDifferentCoursesException;
use App\Exceptions\CannotExchangeEnrollmentWithoutAssociatedShiftException;

class Exchange extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['fromEnrollment', 'toEnrollment'];

    /**
     * The exchanges logger.
     *
     * @var  \App\Judite\Contracts\ExchangeLogger
     */
    private $logger;

    /**
     * Create a new Exchange model instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->logger = resolve(ExchangeLogger::class);
    }

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
        if ($fromEnrollment->exchangesAsSource()->exists()) {
            throw new CannotExchangeEnrollmentMultipleTimesException;
        }

        if ($fromEnrollment->course_id !== $toEnrollment->course_id) {
            throw new CannotExchangeToShiftsOnDifferentCoursesException;
        }

        if (is_null($fromEnrollment->shift_id) || is_null($fromEnrollment->shift_id)) {
            throw new CannotExchangeEnrollmentWithoutAssociatedShiftException;
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
     * Scope a query to only filter exchanges which source enrollment is in a set of values.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $values
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFromEnrollmentIn($query, $values)
    {
        return $query->whereIn('from_enrollment_id', $values);
    }

    /**
     * Scope a query to only filter exchanges which target enrollment is in a set of values.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $values
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereToEnrollmentIn($query, $values)
    {
        return $query->whereIn('to_enrollment_id', $values);
    }

    /**
     * Perform the exchange and update the associated enrollments.
     *
     * @return $this
     */
    public function perform()
    {
        $fromEnrollmentCopy = clone $this->fromEnrollment;
        $toEnrollmentCopy = clone $this->toEnrollment;

        $this->fromEnrollment->exchange($this->toEnrollment);
        $exchangedEnrollments = collect([$this->fromEnrollment, $this->toEnrollment]);
        $this->deleteExchangesOfEnrollments($exchangedEnrollments);

        $this->logger->log($fromEnrollmentCopy, $toEnrollmentCopy);

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

    /**
     * Deletes all exchanges involving the given enrollments.
     *
     * @param  \Illuminate\Support\Collection  $enrollments
     */
    private function deleteExchangesOfEnrollments(Collection $enrollments)
    {
        $enrollmentIds = $enrollments->pluck('id');

        self::whereIn('from_enrollment_id', $enrollmentIds)
            ->orWhereIn('to_enrollment_id', $enrollmentIds)
            ->delete();
    }

    /**
     * Get the course of this exchange.
     *
     * @return \App\Judite\Models\Course
     */
    public function course()
    {
        return $this->fromEnrollment->course;
    }

    /**
     * Get the source shift of this exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function fromShift()
    {
        return $this->fromEnrollment->shift;
    }

    /**
     * Get the target shift of this exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function toShift()
    {
        return $this->toEnrollment->shift;
    }

    /**
     * Get the source student of this exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function fromStudent()
    {
        return $this->fromEnrollment->student;
    }

    /**
     * Get the target student of this exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function toStudent()
    {
        return $this->toEnrollment->student;
    }
}
