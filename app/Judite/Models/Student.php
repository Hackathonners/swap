<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\UserIsAlreadyEnrolledInCourseException;

class Student extends Model
{
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
     * @param  \App\Judite\Models\Course  $course
     * @return \App\Judite\Model\Enrollment
     */
    public function enroll(Course $course)
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
     * @param  \App\Judite\Models\Course  $course
     * @return bool
     */
    public function isEnrolledInCourse(Course $course)
    {
        return $this->enrollments()->where('course_id', $course->id)->exists();
    }
}
