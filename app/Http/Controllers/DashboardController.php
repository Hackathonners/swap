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
        return Auth::user()->isAdmin()
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
            $data['enrollments'] = Auth::student()->enrollments()
                ->withCount('exchangesAsSource')
                ->orderByCourse()
                ->get();
            $data['requestedExchanges'] = Auth::student()->requestedExchanges()->get();
            $data['proposedExchanges'] = Auth::student()->proposedExchanges()->get();

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
