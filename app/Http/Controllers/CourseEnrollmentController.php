<?php

namespace App\Http\Controllers;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\UserIsAlreadyEnrolledInCourseException;

class CourseEnrollmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
        $this->middleware('can.enroll');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function store($id)
    {
        try {
            $course = DB::transaction(function () use ($id) {
                $course = Course::findOrFail($id);
                Auth::student()->enroll($course);

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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $course = DB::transaction(function () use ($id) {
            $course = Course::findOrFail($id);
            Auth::student()->unenroll($course);

            return $course;
        });

        flash("You have successfully deleted the enrollment in {$course->name}.")->success();

        return redirect()->route('courses.index');
    }
}
