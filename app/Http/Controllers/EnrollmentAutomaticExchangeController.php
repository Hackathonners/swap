<?php

namespace App\Http\Controllers;

use App\Judite\Models\Course;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use App\Judite\Models\Shift;
use App\Judite\Models\solver;
use Illuminate\Support\Facades\DB;
use App\Events\ExchangeWasConfirmed;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AutoExchange\CreateRequest;
use App\Exceptions\EnrollmentCannotBeExchangedException;
use App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException;

class EnrollmentAutomaticExchangeController extends Controller
{
	/**
	 * Create a new controller instance.
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('can.student');
		$this->middleware('student.verified');
		$this->middleware('can.exchange');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @param int $id
	 *
	 * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
	 */
	public function create($id)
	{
		try {
			$data = DB::transaction(function () use ($id) {
				$enrollment = Auth::student()->enrollments()->findOrFail($id);
				$course = $enrollment->course()->first();
				$to_shift_tag = $enrollment->shift()->value('tag');

				if (! $enrollment->availableForExchange()) {
					throw new \LogicException('The enrollment is not available for exchange.');
				}

				$matchingShifts = $course->shifts()->where('tag','!=',$to_shift_tag)->orderBy('tag','asc')->get();

				return compact('enrollment','to_shift_tag', 'matchingShifts');
			});

            $data['matchingShifts'] = $data['matchingShifts']->map(function ($item) {
                return [
                    'tag' => $item->tag,
                    '_toString' => $item->present()->inlineToString(),
                ];
            });
			return view('autoExchanges.create', $data);

		} catch (\LogicException $e) {
			flash($e->getMessage())->error();

			return redirect()->route('dashboard');
		}
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param int                                       $id
	 * @param \App\Http\Requests\AutoExchange\CreateRequest $request
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store($id, CreateRequest $request)
	{
		try {

			$exchange = DB::transaction(function () use ($id, $request) {


				$fromEnrollment = Auth::student()->enrollments()->findOrFail($id);
                                $toShift = $fromEnrollment->course()->first()->getShiftByTag($request->input('to_shift_tag'));
				$toEnrollment = Enrollment::make();
				$toEnrollment->student()->associate(null);
				$toEnrollment->course()->associate($fromEnrollment->course()->first());
				$toEnrollment->shift()->associate($toShift);
                               $toEnrollment->save();

				$exchange = Exchange::make();
				$exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
				$exchange->save();

				return $exchange;
			});
            DB::beginTransaction();
	    $message = 'The exchange was successfully saved';
	    flash($message)->success();
            Solver::SolveAutomicExchangesOfCourse($exchange->course());
            DB::commit();

		} catch (EnrollmentCannotBeExchangedException | ExchangeEnrollmentsOnDifferentCoursesException $e) {
			flash($e->getMessage())->error();
		}

		return redirect()->route('dashboard');
	}
}
