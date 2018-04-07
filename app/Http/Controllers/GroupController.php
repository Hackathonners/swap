<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Group;
use App\Judite\Models\Student;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{

    public function __contruct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
    }

    /**
     * @deprecated UNUSED
     * Shows the courses where the authenticated user has groups.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $enrollments = Auth::student()
            ->enrollments()
            ->join('courses','courses.id','=','enrollments.course_id')
            ->where('group_min','>',0);

        return view('groups.index', compact($enrollments));
    }

    /**
     * Shows the users group for the given course.
     * If the user does not have a group redirects to the group
     * create view.
     *
     * @param int $course_id
     *
     * @return \Illuminate\View\View
     */
    public function show($courseId)
    {
        $user_id = Auth::student()->id;
        $group_number = Group::where([
                ['course_id', '=', $courseId],
                ['student_id','=', $user_id],
            ])
            ->select('group_number')
            ->first()
            ->group_number;

        if(is_null($group_number)){
            return redirect('/groups/create/');
        }
        $userIds = Group::whereGroupNumber($group_number)
            ->select('student_id')
            ->get();

        $users = Student::whereIn('id', $userIds)->get();

        return view('groups.show', compact($users));
    }

    /**
     * Stores a new group with the authenticated user.
     *
     * @param int $course_id
     */
    public function store($course_id)
    {
        $student = Auth::user();

        $group = new Group;
        $group->effective = true;
        $group->group_number =
            DB::table('groups')->max('group_number') + 1;
        $group->student_id = $student->id;
        $group->course_id = $course_id;

        $group->save();
    }

    /**
     * Registers an invite to a group of the authenticated user.
     *
     * @param int $groupId
     * @param Request $request
     */
    public function invite($groupId, Request $request)
    {
        $data = $request->validate([
            'student_number' => 'required'
        ]);
        $group = Group::whereId($groupId);

        $invite = new Group;
        $invite->effective = false;
        $invite->group_number = $group->group_number;
        $invite->student_id =
            Student::whereStudentNumber($data['student_number'])->select('id')->first();
        $invite->course_id = $group->course_id;

        $invite->save();

    }

    /**
     * Accepts a group invitation.
     *
     * $groupId is the primary key and not the group number.
     *
     * @param int $groupId
     */
    public function confirm($groupId)
    {
        DB::table('groups')
            ->whereId($groupId)
            ->update(['effective' => true]);
    }

    /**
     * Declines a group invitation.
     *
     * $groupId is the primary key and not the group number.
     *
     * @param int $groupId
     */
    public function decline($groupId)
    {
        DB::table('groups')
            ->whereId($groupId)
            ->delete();
    }

    /**
     * Removes the authenticated user from a group.
     *
     * $groupId is the primary key and not the group number.
     *
     * @param int $groupId
     */
    public function leave($groupId)
    {
        DB::table('groups')
            ->whereId($groupId)
            ->delete();
    }
}
