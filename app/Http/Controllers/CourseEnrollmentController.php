<?php

namespace App\Http\Controllers;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Exceptions\EnrollmentCannotBeDeleted;
use App\Exceptions\StudentIsNotEnrolledInCourseException;
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
     * @return \Illuminate\Http\RedirectResponse
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
            flash("You are already enrolled in {$e->getCourse()->name}.")->error();
        }

        return redirect()->route('courses.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        try {
            $course = DB::transaction(function () use ($id) {
                $course = Course::findOrFail($id);
                Auth::student()->unenroll($course);

                return $course;
            });
            flash("You have successfully deleted the enrollment in {$course->name}.")->success();
        } catch (StudentIsNotEnrolledInCourseException $e) {
            flash('You cannot only delete enrollments that you have enrolled.')->error();
        } catch (EnrollmentCannotBeDeleted $e) {
            flash('You cannot delete an enrollment that already has a shift.')->error();
        }

        return redirect()->route('courses.index');
    }
}
