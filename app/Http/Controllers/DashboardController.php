<?php

namespace App\Http\Controllers;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return auth()->user()->isAdmin()
            ? $this->adminDashboard()
            : $this->studentDashboard();
    }

    /**
     * Get the admin's dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    protected function adminDashboard()
    {
        $courses = DB::transaction(function () {
            return Course::withCount('enrollments')
                ->orderedList()
                ->get();
        });

        $courses = $courses->groupBy(function ($course) {
            return $course->present()->getOrdinalYear();
        });

        return view('admin.dashboard', compact('courses'));
    }

    /**
     * Get the student's dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    protected function studentDashboard()
    {
        $data = DB::transaction(function () {
            $student = Auth::user()->student;
            $data['enrollments'] = $student
                ->enrollments()
                ->orderByCourse()
                ->withCount('exchangesAsSource')
                ->get();
            $data['requestedExchanges'] = $student->requestedExchanges()->get();
            $data['proposedExchanges'] = $student->proposedExchanges()->get();

            return $data;
        });

        // Group all enrollments by the year of their associated course, so
        // the enrollments listing is organized by year. This will allow
        // a better experience, since it matches the official order.
        $data['enrollments'] = $data['enrollments']->groupBy(function ($enrollment) {
            return $enrollment->course->present()->getOrdinalYear();
        });

        return view('dashboard', $data);
    }
}
