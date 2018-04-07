<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Group;
use App\Judite\Models\Student;

class GroupController extends Controller
{
    public function index()
    {
        $courses = Auth::student()
            ->enrolments()
            ->where('group_min','>',0);
        return view('groups.index', compact($courses));
    }

    public function show($id)
    {
        $userIds = Group::whereId($id)
            ->select('user_id')
            ->getBaseQuery();
        $users = Student::whereIn('id', $userIds);
        return view('groups.show', compact($users));
    }

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

    public function invite($groupId, $studentId)
    {
        $group = Group::whereId($groupId);
        $invite = new Group;
        $invite->effective = false;
        $invite->group_number = $group->group_number;
        $invite->student_id = $studentId;
        $invite->course_id = $group->course_id;

        $invite->save();
        
    }

    public function confirm($groupId)
    {
        DB::table('groups')
            ->whereId($groupId)
            ->update(['effective' => true]);
    }

    public function decline($groupId)
    {
        DB::table('groups')
            ->whereId($groupId)
            ->delete();
    }

    public function leave()
    {
        DB::table('groups')
            ->whereId($groupId)
            ->delete();
    }
}
