<?php

namespace App\Http\Controllers\Admin;

use App\Judite\Models\Group;
use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $courses = DB::transaction(function () {
            return Course::orderedList()->get();
        });

        $courses = $courses->groupBy(function ($course) {
            return $course->present()->getOrdinalYear();
        });

        return view('admin.groups.index', compact('courses'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($courseId)
    {
        $groups = Group::whereCourseId($courseId)->get();

        foreach ($groups as $group) {
            $memberships = $group->memberships()->get();

            $students = [];
            foreach ($memberships as $membershipKey => $membership) {
                $student = $membership->student()->first();
                $student->name = $student->user()->first()->name;
                array_push($students, $student);
            }
            $group->students = $students;
        }

        return view('admin.groups.show', compact('groups'));
    }
}
