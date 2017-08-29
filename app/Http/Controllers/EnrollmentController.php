<?php

namespace App\Http\Controllers;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UserIsAlreadyEnrolledInCourseException;

class EnrollmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('can.enroll');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $courseId
     *
     * @return \Illuminate\Http\Response
     */
    public function store($courseId)
    {
        try {
            $course = DB::transaction(function () use ($courseId) {
                $course = Course::findOrFail($courseId);
                Auth::user()->student->enroll($course);

                return $course;
            });

            flash("You have successfully enrolled in {$course->name}.")->success();
        } catch (UserIsAlreadyEnrolledInCourseException $e) {
            $course = $e->getCourse();
            flash("You are already enrolled in {$course->name}.")->error();
        }

        return redirect()->route('courses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $courseId
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($courseId)
    {
        $course = DB::transaction(function () use ($courseId) {
            $course = Course::findOrFail($courseId);
            student()->unenroll($course);

            return $course;
        });

        flash("You have successfully deleted the enrollment in {$course->name}.")->success();

        return redirect()->route('courses.index');
    }
}
