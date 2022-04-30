<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;
use App\Judite\Presenters\EnrollmentPresenter;

class Enrollment extends Model
{
    use PresentableTrait;

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['student', 'shift', 'course'];

    /**
     * The presenter for this entity.
     *
     * @var string
     */
    protected $presenter = EnrollmentPresenter::class;

    /**
     * Scope a query to filter enrollments by owner.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Judite\Models\Student            $student
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOwnedBy($query, Student $student)
    {
        return $query->whereStudentId($student->id);
    }

    /**
     * Scope a query to order enrollments by course.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByCourse($query)
    {
        return $query->select('enrollments.*')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->orderBy('courses.year', 'asc')
            ->orderBy('courses.semester', 'asc')
            ->orderBy('courses.name', 'asc');
    }

    /**
     * Scope a query to filter similar enrollments.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Judite\Models\Enrollment         $enrollment
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSimilarEnrollments($query, self $enrollment)
    {
        return $query->where('enrollments.id', '!=', $enrollment->id)
            ->where('course_id', $enrollment->course->id)
            ->where('shift_id', '!=', $enrollment->shift->id);
    }

    /**
     * Scope a query to order enrollments by students.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrderByStudent($query)
    {
        return $query->select('enrollments.*')
            ->join('students', 'enrollments.student_id', '=', 'students.id')
            ->orderBy('students.student_number', 'asc');
    }

    /**
     * Exchange shifts with the given enrollment.
     *
     * @param \App\Judite\Models\Enrollment $enrollment
     *
     * @return $this
     */
    public function exchange(self $enrollment): self
    {
        $fromShiftId = $this->shift_id;
        $this->shift()->associate($enrollment->shift_id);
        $enrollment->shift()->associate($fromShiftId);

        $this->save();
		$enrollment->save();

        return $this;
    }

    /**
     * Get exchanges of this enrollment as source.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function exchangesAsSource()
    {
        return $this->hasMany(Exchange::class, 'from_enrollment_id');
    }

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
     * Check whether this enrollment is available for exchange.
     *
     * @return bool
     */
    public function availableForExchange()
    {
        $isBeingExchanged = is_null($this->exchanges_as_source_count)
            ? $this->exchangesAsSource()->exists()
            : $this->exchanges_as_source_count > 0;

        return ! $isBeingExchanged && ! is_null($this->shift_id);
    }

    /**
     * Check if a enrollment can be deleted.
     *
     * @return bool
     */
    public function isDeletable()
    {
        return is_null($this->shift);
    }
}
