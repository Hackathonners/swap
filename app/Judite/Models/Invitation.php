<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     * Get the student of this invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the group of this invitation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    /**
     * Scope a query to get the student amount of invitations in a given course.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $studentNumber
     * @param int $courseId
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfStudentInCourse($query, $studentNumber, $courseId)
    {
        return $query->where([
            ['course_id', '=', $courseId],
            ['student_number', '=', $studentNumber],
            ]);
    }
}
