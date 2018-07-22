<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
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

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $course = Course::find($id);

        $min = $request->input('min');
        $max = $request->input('max');

        if ($min > $max) {
            $max = $min;
        }

        $course->group_min = $min;
        $course->group_max = $max;

        $course->save();

        return redirect()->route('admin.groups.index');
    }
}
