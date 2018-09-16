<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;
use Illuminate\Support\Facades\DB;
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
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $student = Auth::student();

        $enrollments = $student
            ->enrollments()
            ->orderByCourse()
            ->get();

        foreach ($enrollments as $enrollmentKey => $enrollment) {
            $course = DB::transaction(function () use ($enrollment) {
                return Course::whereId($enrollment->course_id)->first();
            });

            if ($course->group_max <= 0) {
                unset($enrollments[$enrollmentKey]);
                continue;
            }

            $membership = $student->findMembershipByCourse($course->id);

            if (is_null($membership)) {
                $enrollment->group_status = 0;
            } else {
                $group = DB::transaction(function () use ($membership) {
                    return Group::with('memberships')
                        ->whereId($membership->group_id)
                        ->first();
                });

                $enrollment->group_status = $group->memberships->count();
            }

            $enrollment->name = $course->name;
            $enrollment->group_min = $course->group_min;
            $enrollment->group_max = $course->group_max;

            $enrollment->number_invitations = Invitation::ofStudentInCourse(
                    $student->student_number,
                    $enrollment->course_id
                )->count();
        }

        return view('groups.index', compact('enrollments'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($courseId)
    {
        $group = DB::transaction(function () use ($courseId) {
            if (! Course::find($courseId)->exists()) {
                return redirect()->back();
            }

            $group = new Group();
            $group->course_id = $courseId;
            $group->save();

            return $group;
        });

        try {
            Auth::student()->join($group);

            flash('You have successfully joined a group.')->success();
        } catch (UserHasAlreadyGroupInCourseException $e) {
            flash('You already have a group.')->error();
            $group->delete();
        }

        return redirect()->route('groups.show', compact('courseId'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $courseId
     *
     * @return \Illuminate\View\View
     */
    public function show($courseId)
    {
        $student = Auth::student();

        $course = DB::transaction(function () use ($courseId, $student) {
            $course = Course::whereId($courseId)->first();

            $course->number_invitations = Invitation::ofStudentInCourse(
                    $student->student_number,
                    $courseId
                )->count();

            return $course;
        });

        $membership = DB::transaction(function () use ($courseId, $student) {
            return $student->memberships()
                ->with('group.memberships.student.user')
                ->whereCourseId($courseId)
                ->first();
        });

        return view('groups.show', compact('course', 'membership'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $inviteId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($inviteId)
    {
        $invitation = DB::transaction(function () use ($inviteId) {
            return Invitation::with(['group.memberships', 'group.course'])
                ->whereId($inviteId)
                ->first();
        });

        $numberOfGroupMembers = $invitation->group->memberships->count();
        $groupMaxSize = $invitation->group->course->group_max;

        if ($numberOfGroupMembers >= $groupMaxSize) {
            flash('Course group limit exceeded')->error();

            return redirect()->back();
        }

        try {
            Auth::student()->join($invitation->group);

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
     * @param int $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($courseId)
    {
        $group = Auth::student()->memberships()->get()
            ->where('course_id', '=', $courseId)->first()
            ->group()->first();

        Auth::student()->leave($group);

        if (! $group->memberships()->count()) {
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
}
