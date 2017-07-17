<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Illuminate\Http\Request;
use App\Judite\Models\Course;
use App\Judite\Models\Enrollment;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $enrollment = DB::transaction(function () use ($request) {
            $this->validate($request, [
                'course_id' => 'exists:courses,id',
            ]);

            $student = Auth::user()->student;
            $course = Course::find($request->input('course_id'));
            $enrollment = $student->enroll($course);

            return $enrollment;
        });

        return $enrollment;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Exports the list of students enrolled in each course.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        // Check for export authorization
        $this->authorize('export', Enrollment::class);

        // Get the list of students enrolled on each course
        $enrollments = DB::transaction(function () {
            $enrollments = Enrollment::with(['student', 'course'])
                                     ->get()
                                     ->sortByDesc('courses.name');

            return $enrollments;
        });

        // Export to CSV
        $result = Excel::create('enrollments', function ($excel) use ($enrollments) {
            $excel->sheet('Enrollments', function ($sheet) use ($enrollments) {
                $sheet->loadView('enrollments.export', compact('enrollments'));
            });
        });

        return $result->export('csv');
    }
}
