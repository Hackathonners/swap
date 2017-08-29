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
     * @return \Illuminate\View\View
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
     * @return \Illuminate\View\View
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
     * @return \Illuminate\View\View
     */
    protected function studentDashboard()
    {
        $data = DB::transaction(function () {
            $proposedExchanges = Auth::student()->proposedExchanges()->get();
            $requestedExchanges = Auth::student()->requestedExchanges()->get();
            $enrollments = Auth::student()->enrollments()
                ->withCount('exchangesAsSource')
                ->orderByCourse()
                ->get();

            return compact('proposedExchanges', 'requestedExchanges', 'enrollments');
        });

        $data['enrollments'] = $data['enrollments']->groupBy(function ($enrollment) {
            return $enrollment->course->present()->getOrdinalYear();
        });

        return view('dashboard', $data);
    }
}
