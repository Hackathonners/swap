<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UserHasAlreadyGroupInCourseException;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
        $this->middleware('can.group');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = Auth::student();

        $enrollments = $student
            ->enrollments()
            ->orderByCourse()
            ->get();

        foreach ($enrollments as $enrollmentKey => $enrollment) {
            $course = Course::whereId($enrollment->course_id)->first();

            if ($course->group_max <= 0) {
                unset($enrollments[$enrollmentKey]);
                continue;
            }

            $membership = $student->memberships()
                ->whereCourseId($course->id)
                ->first();

            if (is_null($membership)) {
                $enrollment->group_status = 0;
            } else {
                $group = Group::whereId($membership->group_id)->first();
                $enrollment->group_status =
                    $group->memberships()->count();
            }

            $enrollment->name = $course->name;
            $enrollment->group_min = $course->group_min;
            $enrollment->group_max = $course->group_max;

            $enrollment->number_invitations = $this->numberInvitations(
                $enrollment->course_id,
                $student->student_number
            );
        }

        return view('groups.index', compact('enrollments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $courseId
     *
     * @return \Illuminate\Http\Response
     */
    public function store($courseId)
    {
        $group = new Group();
        $group->course_id = $courseId;
        $group->save();

        try {
            Auth::student()->join($group);

            flash('You have successfully joined a group.')
                ->success();
        } catch (UserHasAlreadyGroupInCourseException $e) {
            flash('You already have a group.')
                ->error();
            $group->delete();
        }

        return redirect()->route('groups.show', compact('courseId'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $courseId
     *
     * @return \Illuminate\Http\Response
     */
    public function show($courseId)
    {
        $student = Auth::student();

        $course = Course::whereId($courseId)->first();

        $course->numberInvitations = $this->numberInvitations(
            $courseId,
            $student->student_number
        );

        $membership = $student
            ->memberships()
            ->whereCourseId($courseId)
            ->first();

        $students = [];
        $group = 0;
        if (! is_null($membership)) {
            $group = $membership->group()->first();
            $memberships = $group->memberships()->get();

            foreach ($memberships as $membership) {
                $student = $membership->student()->first();

                $student->name = $student->user()->first()->name;

                array_push($students, $student);
            }
        }

        return view('groups.show', compact('course', 'students', 'group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function update($inviteId)
    {
        $invitation = Invitation::whereId($inviteId)->first();

        $group = Group::whereId($invitation->group_id)->first();

        $courseMax = Course::whereId($invitation->course_id)
            ->first()
            ->group_max;

        if ($group->memberships()->count() >= $courseMax) {
            flash('Course group limit exceeded')->error();

            return redirect()->back();
        }

        try {
            Auth::student()->join($group);

            flash('You have successfully joined the group.')
                ->success();

            return redirect()->route('invitations.destroy', compact('inviteId'));
        } catch (UserHasAlreadyGroupInCourseException $e) {
            flash('You already have a group.')
                ->error();

            $courseId = $invitation->course_id;

            return redirect()->route('groups.show', compact('courseId'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($courseId)
    {
        $group = Auth::student()->memberships()->get()
            ->where('course_id', '=', $courseId)->first()
            ->group()->first();

        Auth::student()->leave($group);

        if (! ($group->memberships()->count())) {
            $invitations = Invitation::whereGroupId($group->id)->get();

            foreach ($invitations as $invitation) {
                $invitation->delete();
            }

            $group->delete();
            flash('Group deleted.')->success();
        } else {
            flash('You have successfully left the group.')->success();
        }

        return redirect()->route('groups.show', compact('courseId'));
    }

    private function numberInvitations($courseId, $studentNumber)
    {
        return Invitation::where([
            ['course_id', '=', $courseId],
            ['student_number', '=', $studentNumber],
            ])
            ->count();
    }
}
