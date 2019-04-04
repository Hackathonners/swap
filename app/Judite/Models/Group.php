<?php

namespace App\Judite\Models;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\GroupIsFullException;
use App\Exceptions\StudentIsNotEnrolledInCourseException;
use App\Exceptions\StudentHasAlreadyGroupInCourseException;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get course of this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get students for this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(Student::class)
                    ->as('invitation')
                    ->withPivot('confirmed_at');
    }

    /**
     * Get confirmed students for this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function confirmedStudents()
    {
        return $this->students()->wherePivot('confirmed_at', '!=', null);
    }

    /**
     * Get pending students for this group.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function pendingStudents()
    {
        return $this->students()->wherePivot('confirmed_at', '=', null);
    }

    /**
     * Check if this group is eligible for acceptance.
     *
     * @return bool
     */
    public function isEligibleForAcceptance()
    {
        $currentStudentsCount = $this->relationLoaded('students')
            ? $this->students->count()
            : $this->students()->count();

        return $currentStudentsCount >= app('settings')->min_group_members;
    }

    /**
     * Check if this group is full.
     *
     * @return bool
     */
    public function isFull()
    {
        $currentStudentsCount = $this->relationLoaded('students')
            ? $this->stisEligibleForAcceptanceudents->count()
            : $this->students()->count();

        return $currentStudentsCount === app('settings')->max_group_members;
    }

    /**
     * Check if this group is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return ! $this->students()->exists();
    }

    /**
     * Check if this group is available.
     *
     * @return bool
     */
    public function isAvailableToJoin()
    {
        $currentStudentsCount = $this->relationLoaded('students')
            ? $this->students->count()
            : $this->students()->count();

        return $currentStudentsCount < app('settings')->max_group_members;
    }

    /**
     * Add the given student to this group.
     *
     * @param \App\Judite\Models\Student $student
     *
     * @throws \App\Exceptions\GroupIsFullException
     *
     * @return bool
     */
    public function addMember(Student $student)
    {
        throw_if($this->isFull(), new GroupIsFullException('The group is full.'));
        
        // throw_if(
        //     $student->hasGroupInCourse($this->course),
        //     new StudentHasAlreadyGroupInCourseException()
        // );

        throw_unless(
            $student->isEnrolledInCourse($this->course), 
            new StudentIsNotEnrolledInCourseException($this->course)
        );

        if ($student->isAssociatedToGroup($this)) {
            return true;
        }

        return $this->students()->save($student);
    }

    /**
     * Remove the given member from this group.
     *
     * @param \App\Judite\Models\Student $member
     *
     * @return $this
     */
    public function removeMember(Student $member)
    {
        if (! $member->isAssociatedToGroup($this)) {
            return $this;
        }

        $this->students()->detach($member->id);
        $this->save();

        if ($this->isEmpty()) {
            $this->delete();
        }

        return $this;
    }
}
