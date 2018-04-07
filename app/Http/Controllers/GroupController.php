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

    public function store()
    {
        //
    }

    public function edit()
    {
        //
    }

    public function update()
    {
        //
    }

    public function invite($userId)
    {
        //
    }

    public function confirm()
    {
        //
    }

    public function decline()
    {
        //
    }

    public function leave()
    {
        //
    }
}
