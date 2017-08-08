<?php

namespace App\Http\Controllers;

use DB;
use App\Judite\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $courses = DB::transaction(function () {
            return Course::orderedList()->get();
        });

        $courses = $courses->groupBy(function ($course) {
            return $course->present()->getOrdinalYear();
        });

        return view('courses.index', compact('courses'));
    }

    /**
     * Display a resource of given id.
     *
     * @param int $id ID of a Course
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = DB::transaction(function () use ($id) {
            $data['course'] = Course::findOrFail($id);
            $data['enrollments'] = $data['course']->enrollments()->with('student.user')->paginate();

            return $data;
        });

        return view('students.index', $data);
    }
}
