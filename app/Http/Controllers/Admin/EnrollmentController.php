<?php

namespace App\Http\Controllers\Admin;

use DB;
use App\Judite\Models\Course;
use App\Judite\Models\Enrollment;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class EnrollmentController extends Controller
{
    /**
     * Exports the list of students enrolled in each course.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
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
