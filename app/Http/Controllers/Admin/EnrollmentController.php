<?php

namespace App\Http\Controllers\Admin;

use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exceptions\InvalidFieldValueException;
use App\Exceptions\InvalidImportFileException;
use App\Http\Requests\Enrollment\ImportRequest;

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

    /**
     * Store the enrollments imported in the request.
     *
     * @param  \App\Http\Requests\Enrollment\ImportRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeImport(ImportRequest $request)
    {
        try {
            $file = $request->enrollments;
            $path = $file->path();
            $filename = $file->getClientOriginalName();

            Excel::load($path, function ($reader) use ($filename) {
                DB::transaction(function () use ($filename, $reader) {
                    // Loop for all the rows of the table
                    $index = 1;
                    $reader->each(function ($row) use (&$index) {
                        // Calculate the row index
                        $index++;

                        // Get the models of the given ids
                        // Check if the given student number exists
                        $student = Student::whereNumber($row->student_id)->first();
                        if ($student === null) {
                            $exception = new InvalidFieldValueException;
                            $exception->setField('Student ID', $row->student_id, "The student {$row->student_id} does not exist. (at line {$index})");
                            throw $exception;
                        }

                        // Check if the given course id exists
                        $course = Course::find($row->course_id);
                        if ($course === null) {
                            $exception = new InvalidFieldValueException;
                            $exception->setField('Course ID', $row->course_id, "The course {$row->course_id} does not exist. (at line {$index})");
                            throw $exception;
                        }

                        // Check if the given shift tag exists in the associated course
                        if ($row->shift === null) {
                            $exception = new InvalidFieldValueException;
                            $exception->setField('Shift', $row->shift, "The shift field is required on imported enrollments. (at line {$index})");
                            throw $exception;
                        }

                        $shift = $course->getShiftByTag($row->shift);
                        if ($shift === null) {
                            $shift = Shift::make(['tag' => $row->shift]);
                            $course->addShift($shift);
                        }

                        // Check if the enrollment exists
                        $enrollment = Enrollment::where([
                            'course_id' => $course->id,
                            'student_id' => $student->id,
                        ])->first();
                        if ($enrollment === null) {
                            $exception = new InvalidImportFileException("The student {$row->student_id} is not enrolled in course {$row->course_id}. (at line {$index})");
                            throw $exception;
                        }

                        // Add the shift to the enrollment
                        $enrollment->shift()->associate($shift);
                        $enrollment->save();
                    });
                });
            });

            flash('The enrollments file was successfully imported.')->success();
        } catch (InvalidImportFileException $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('enrollments.import');
    }

    /**
     * Show the form for importing enrollments.
     *
     * @return \Illuminate\View\View
     */
    public function import()
    {
        return view('enrollments.import');
    }
}
