<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\EnrollmentCannotBeDeleted;
use App\Exceptions\StudentIsNotMemberOfGroupException;
use App\Exceptions\StudentIsNotInvitedToGroupException;
use App\Exceptions\StudentIsNotEnrolledInCourseException;
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

    protected $visible = ['user','student_number'];

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
     * Get groups for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)
                    ->as('invitation')
                    ->withPivot('accepted_at');
    }

    /**
     * Get pending groups for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pendingGroups()
    {
        return $this->groups()->wherePivot('accepted_at', '=', null);
    }

    /**
     * Get confirmed groups for this student.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function confirmedGroups()
    {
        return $this->groups()->wherePivot('accepted_at', '!=', null);
    }

    /**
     * Get enrollment of this student in a given course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @return \App\Judite\Models\Enrollment|null
     */
    public function getEnrollmentInCourse(Course $course)
    {
        return $this->enrollments()
            ->where('course_id', $course->id)
            ->first();
    }

    /**
     * Get group of this student in a given course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @return \App\Judite\Models\Group|null
     */
    public function getGroupInCourse(Course $course)
    {
        return $this->groups()
            ->where('course_id', $course->id)
            ->first();
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
     * @throws \App\Exceptions\StudentIsNotEnrolledInCourseException|\App\Exceptions\EnrollmentCannotBeDeleted
     *
     * @return bool
     */
    public function unenroll(Course $course): bool
    {
        $enrollment = $this->getEnrollmentInCourse($course);

        if (is_null($enrollment)) {
            throw new StudentIsNotEnrolledInCourseException($course);
        }

        if (! $enrollment->isDeletable()) {
            throw new EnrollmentCannotBeDeleted($enrollment);
        }

        return $enrollment->delete();
    }

    /**
     * Confirm this student with a given group.
     *
     * @param \App\Judite\Models\Group $group
     *
     * @return \App\Judite\Models\Student
     */
    public function confirmGroup(Group $group): self
    {
        throw_unless(
            $this->isInvitedToGroup($group),
            new StudentIsNotMemberOfGroupException()
        );

        $atributtes = [
            'accepted_at' => now(),
        ];

        $this->groups()->updateExistingPivot($group->id, $atributtes);

        return $this;
    }

    /**
     * Decline this student with a given group.
     *
     * @param \App\Judite\Models\Group $group
     *
     * @return \App\Judite\Models\Student
     */
    public function declineGroup(Group $group): self
    {
        throw_unless(
            $this->isInvitedToGroup($group),
            new StudentIsNotInvitedToGroupException()
        );

        $group->removeMember($this);

        return $this;
    }

    /**
     * Leave the given group.
     *
     * @param \App\Judite\Models\Group $group
     *
     * @return \App\Judite\Models\Student
     */
    public function leaveGroup(Group $group): self
    {
        throw_unless(
            $this->isMemberOfGroup($group),
            new StudentIsNotMemberOfGroupException()
        );

        $group->removeMember($this);

        return $this;
    }

    /**
     * Check if this student has a group in the given course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @return bool
     */
    public function hasGroupInCourse(Course $course): bool
    {
        return $this->confirmedGroups()->where('course_id', $course->id)->exists();
    }

    /**
     * Check if this student has an eligible group in the given course.
     *
     * @param \App\Judite\Models\Course $course
     *
     * @return bool
     */
    public function hasAvailableGroupInCourse(Course $course)
    {
        return $this->hasGroupInCourse($course) &&
        $this->getGroupInCourse($course)->isAvailableToJoin();
    }

    /**
     * Check if this student is attached to the given group.
     *
     * @param \App\Judite\Models\Group $group
     *
     * @return bool
     */
    public function isAssociatedToGroup(Group $group): bool
    {
        return $this->groups()->where('group_id', $group->id)->exists();
    }

    /**
     * Check if this student is invited to the given group.
     *
     * @param \App\Judite\Models\Group $group
     *
     * @return bool
     */
    public function isInvitedToGroup(Group $group): bool
    {
        return $this->pendingGroups()->where('group_id', $group->id)->exists();
    }

    /**
     * Check if this student is a member of the given group.
     *
     * @param \App\Judite\Models\Group $group
     *
     * @return bool
     */
    public function isMemberOfGroup(Group $group): bool
    {
        return $this->confirmedGroups()->where('group_id', $group->id)->exists();
    }

    public function getCoursesWithoutGroup()
    {
        $enrollments = $this->enrollments()->get();
        $courses = collect([]);
        foreach($enrollments as $enrollment) {
            if(! $this->hasGroupInCourse($enrollment->course()->first())) {
                $courses->prepend($enrollment->course()->first());
            }
        }
        return $courses;
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
