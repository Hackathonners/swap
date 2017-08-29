<?php

namespace App\Http\Controllers\Admin;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CourseController extends Controller
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
     * Display a resource of given id.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $data = DB::transaction(function () use ($id) {
            $course = Course::findOrFail($id);
            $enrollments = $course->enrollments()
                ->with('student.user')
                ->orderByStudent()
                ->paginate();

            return compact('course', 'enrollments');
        });

        return view('students.index', $data);
    }
}
