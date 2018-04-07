<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Group;
use App\Judite\Models\Student;

class GroupController extends Controller
{

    public function __contruct()
    {
        $this->middleware('auth');
        $this->middleware('can.admin');
    }

    public function index($course_id)
    {
        $groups = Group::whereCourseId($course_id)
            ->select(['id','group_number']);
        return view('groups.index', compact($groups));
    }

    public function show($id)
    {
        $userIds = Group::whereId($id)
            ->select('user_id')
            ->getBaseQuery();
        
        $users = Student::whereIn('id', $userIds)
            ->join('users','users.id','=','students.user_id')
            ->select(['student_number', 'name']);
        
        return view('groups.show', compact($users));
    }
}
