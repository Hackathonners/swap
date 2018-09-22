<?php

namespace App\Judite\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\UserHasAlreadyAnInviteInGroupException;

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
     * @param string                                $studentNumber
     * @param int                                   $courseId
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

    /**
     * Saves new invitation.
     *
     * @param $studentNumber
     * @param $groupId
     * @param $courseId
     *
     * @throws \App\Exceptions\UserHasAlreadyAnInviteInGroupException
     *
     * @return invitation
     */
    public static function create($studentNumber, $groupId, $courseId)
    {
        $findAnyInvitation = DB::transaction(function () use ($studentNumber, $groupId) {
            return self::where([
                    ['student_number', $studentNumber],
                    ['group_id', $groupId],
                ]);
        });

        if ($findAnyInvitation->exists()) {
            throw new UserHasAlreadyAnInviteInGroupException();
        }

        $invitation = new self();
        $invitation->student_number = $studentNumber;
        $invitation->group_id = $groupId;
        $invitation->course_id = $courseId;

        $invitation->save();

        return $invitation;
    }
}
