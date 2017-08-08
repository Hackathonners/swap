<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Judite\Models\Course;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
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
            $data['enrollments'] = $data['course']->enrollments()
                ->with('student.user')
                ->orderByStudent()
                ->paginate();

            return $data;
        });

        return view('students.index', $data);
    }
}
