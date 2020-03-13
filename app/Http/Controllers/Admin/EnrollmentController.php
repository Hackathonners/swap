<?php

namespace App\Http\Controllers\Admin;

use App\Judite\Models\Course;
use App\Exports\EnrollmentsExport;
use App\Imports\EnrollmentsImport;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Events\ImportFailed;
use App\Exceptions\InvalidImportFileException;
use App\Http\Requests\Enrollment\ImportRequest;

class EnrollmentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.admin');
    }

    /**
     * Exports the list of students enrolled in each course.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {
        return (new EnrollmentsExport())->download('enrollments.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    /**
     * Store the enrollments imported in the request.
     *
     * @param \App\Http\Requests\Enrollment\ImportRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeImport(ImportRequest $request)
    {
        try {
            DB::transaction(fn () => Excel::import(new EnrollmentsImport(), $request->file('enrollments')));

            flash('The enrollments file was successfully imported.')->success();
        } catch (InvalidImportFileException $e) {
            flash($e->getMessage())->error();
        } catch (ImportFailed $e) {
            flash('Could not import the enrollments file. Please try again.')->error();
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
