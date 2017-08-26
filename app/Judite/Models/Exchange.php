<?php

namespace App\Judite\Models;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Judite\Contracts\Registry\ExchangeRegistry;
use App\Exceptions\MultipleEnrollmentExchangesException;
use App\Exceptions\ExchangeEnrollmentWithoutShiftException;
use App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException;

class Exchange extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['fromEnrollment', 'toEnrollment'];

    /**
     * The exchanges registry.
     *
     * @var \App\Judite\Contracts\Registry\ExchangeRegistry
     */
    private $registry;

    /**
     * Create a new Exchange model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->registry = resolve(ExchangeRegistry::class);
    }

    /**
     * Set the enrollments of this exchange.
     *
     * @param \App\Judite\Models\Enrollment $from
     * @param \App\Judite\Models\Enrollment $to
     *
     * @throws \App\Exceptions\MultipleEnrollmentExchangesException
     * @throws \App\Exceptions\ExchangeEnrollmentWithoutShiftException
     * @throws \App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException
     *
     * @return $this
     */
    public function setExchangeEnrollments(Enrollment $from, Enrollment $to) : Exchange
    {
        // Each enrollment can be requested to exchange once. The students
        // are allowed to create only a single exchange related to the
        // same enrollment, ensuring that each request exists once.
        if ($from->exchangesAsSource()->exists()) {
            throw new MultipleEnrollmentExchangesException();
        }

        if ($from->course_id !== $to->course_id) {
            throw new ExchangeEnrollmentsOnDifferentCoursesException();
        }

        if (is_null($from->shift_id) || is_null($to->shift_id)) {
            throw new ExchangeEnrollmentWithoutShiftException();
        }

        $this->fromEnrollment()->associate($from);
        $this->toEnrollment()->associate($to);

        return $this;
    }

    /**
     * Check if an inverse exchange exists.
     *
     * @param \App\Judite\Models\Enrollment $from
     * @param \App\Judite\Models\Enrollment $to
     *
     * @return \App\Judite\Models\Exchange|null
     */
    public static function findMatchingExchange(Enrollment $from, Enrollment $to): ?Exchange
    {
        $inverseMatch = [
            'from_enrollment_id' => $to->id,
            'to_enrollment_id' => $from->id,
        ];

        return self::where($inverseMatch)->first();
    }

    /**
     * Scope a query to only filter exchanges which source enrollment is in a set of values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $values
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereFromEnrollmentIn($query, $values)
    {
        return $query->whereIn('from_enrollment_id', $values);
    }

    /**
     * Scope a query to only filter exchanges which target enrollment is in a set of values.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed                                 $values
     *
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
    public function perform(): Exchange
    {
        $fromEnrollmentCopy = clone $this->fromEnrollment;
        $toEnrollmentCopy = clone $this->toEnrollment;

        $this->fromEnrollment->exchange($this->toEnrollment);
        $exchangedEnrollments = collect([$this->fromEnrollment, $this->toEnrollment]);
        $this->deleteExchangesOfEnrollments($exchangedEnrollments);

        $this->registry->record($fromEnrollmentCopy, $toEnrollmentCopy);

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
     * @param \Illuminate\Support\Collection $enrollments
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
    public function course(): Course
    {
        return $this->fromEnrollment->course;
    }

    /**
     * Get the source shift of this exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function fromShift(): Shift
    {
        return $this->fromEnrollment->shift;
    }

    /**
     * Get the target shift of this exchange.
     *
     * @return \App\Judite\Models\Shift
     */
    public function toShift(): Shift
    {
        return $this->toEnrollment->shift;
    }

    /**
     * Get the source student of this exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function fromStudent(): Student
    {
        return $this->fromEnrollment->student;
    }

    /**
     * Get the target student of this exchange.
     *
     * @return \App\Judite\Models\Student
     */
    public function toStudent(): Student
    {
        return $this->toEnrollment->student;
    }
}
