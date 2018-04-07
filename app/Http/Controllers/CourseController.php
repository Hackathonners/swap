<?php

namespace App\Http\Controllers;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
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

        return view('courses.index', compact('courses'));
    }

    /**
     * Change the minimum group size for the given course
     *
     * @param int $course_id
     * @param int $min
     */
    public function setGroupMin($course_id, $min){
        Course::whereCourseId($course_id)
            ->update(['group_min' => $min]);
    }

    /**
     * Change the maximum group size for the given course
     *
     * @param int $course_id
     * @param int $max
     */
    public function setGroupMax($course_id, $max){
        Course::whereCourseId($course_id)
            ->update(['group_max' => $max]);
    }
}
