<?php

namespace App\Http\Controllers;

use DB;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the student dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::transaction(function () {
            $student = Auth::user()->student;
            $data['enrollments'] = $student->enrollments()->orderByCourse()->get();
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
