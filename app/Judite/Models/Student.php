<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\UserIsAlreadyEnrolledInCourseException;

class Student extends Model
{
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['user'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['student_number'];

    /**
     * Get user who owns this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get exchanges requested by this student.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function requestedExchanges()
    {
        $enrollmentsRelationship = $this->enrollments();
        $enrollmentsKeyName = $enrollmentsRelationship->getRelated()->getKeyName();
        $enrollmentsIdsQuery = $enrollmentsRelationship
            ->select($enrollmentsKeyName)
            ->getBaseQuery();

        return Exchange::whereFromEnrollmentIn($enrollmentsIdsQuery);
    }

    /**
     * Get exchanges proposed to this student.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function proposedExchanges()
    {
        $enrollmentsRelationship = $this->enrollments();
        $enrollmentsKeyName = $enrollmentsRelationship->getRelated()->getKeyName();
        $enrollmentsIdsQuery = $enrollmentsRelationship
            ->select($enrollmentsKeyName)
            ->getBaseQuery();

        return Exchange::whereToEnrollmentIn($enrollmentsIdsQuery);
    }

    /**
     * Get enrollments of this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Enroll this student with a given course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @throws \App\Exceptions\UserIsAlreadyEnrolledInCourseException
     *
     * @return \App\Judite\Models\Enrollment
     */
    public function enroll(Course $course): Enrollment
    {
        if ($this->isEnrolledInCourse($course)) {
            throw new UserIsAlreadyEnrolledInCourseException($course);
        }

        $enrollment = $this->enrollments()->make();
        $enrollment->course()->associate($course);
        $enrollment->save();

        return $enrollment;
    }

    /**
     * Check if this student is enrolled in a course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @return bool
     */
    public function isEnrolledInCourse(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }

    /**
     * Remove enrollment in the given course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @return bool
     */
    public function unenroll(Course $course): bool
    {
        return $this->enrollments()->where('course_id', $course->id)->delete();
    }

    /**
     * Scope a query to only include users with the given student number.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $studentNumber
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereNumber($query, $studentNumber)
    {
        return $query->where('student_number', $studentNumber);
    }
}
