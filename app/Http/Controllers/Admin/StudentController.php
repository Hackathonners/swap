<?php

namespace App\Http\Controllers\Admin;

use App\Judite\Models\Student;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Judite\Contracts\Registry\ExchangeRegistry;

class StudentController extends Controller
{
    /**
     * Display a resource of given id.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id, ExchangeRegistry $exchangeLogger)
    {
        $data = DB::transaction(function () use ($id, $exchangeLogger) {
            $data = [];
            $student = Student::findOrFail($id);
            $data['student'] = $student;
            $data['enrollments'] = $student
                ->enrollments()
                ->orderByCourse()
                ->withCount('exchangesAsSource')
                ->get();
            $data['requestedExchanges'] = $student->requestedExchanges()->get();
            $data['proposedExchanges'] = $student->proposedExchanges()->get();
            $data['historyExchanges'] = $exchangeLogger->historyOfStudent($student);

            return $data;
        });

        // Group all enrollments by the year of their associated course, so
        // the enrollments listing is organized by year. This will allow
        // a better experience, since it matches the official order.
        $data['enrollments'] = $data['enrollments']->groupBy(function ($enrollment) {
            return $enrollment->course->present()->getOrdinalYear();
        });

        return view('students.show', $data);
    }
}
