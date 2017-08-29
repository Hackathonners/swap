<?php

namespace App\Http\Controllers;

use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use App\Events\ExchangeWasConfirmed;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Exchange\CreateRequest;
use App\Exceptions\EnrollmentCannotBeExchangedException;
use App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException;

class EnrollmentExchangeController extends Controller
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

                if (! $enrollment->availableForExchange()) {
                    throw new \LogicException('The enrollment is not available for exchange.');
                }

                $matchingEnrollments = Enrollment::similarEnrollments($enrollment)
                    ->orderByStudent()
                    ->get();

                return compact('enrollment', 'matchingEnrollments');
            });

            $data['matchingEnrollments'] = $data['matchingEnrollments']->map(function ($item) {
                return [
                    'id' => $item->id,
                    '_toString' => $item->present()->inlineToString(),
                ];
            });

            return view('exchanges.create', $data);
        } catch (\LogicException $e) {
            flash($e->getMessage())->error();

            return redirect()->route('dashboard');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int                                       $id
     * @param \App\Http\Requests\Exchange\CreateRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($id, CreateRequest $request)
    {
        try {
            $exchange = DB::transaction(function () use ($id, $request) {
                $this->validate($request, [
                    'to_enrollment_id' => 'exists:enrollments,id',
                ]);

                $fromEnrollment = Auth::student()->enrollments()->findOrFail($id);
                $toEnrollment = Enrollment::find($request->input('to_enrollment_id'));

                // Firstly check if the inverse exchange for the same enrollments
                // already exists. If the inverse record is found then we will
                // exchange and update both enrollments of this exchange.
                if ($exchange = Exchange::findMatchingExchange($fromEnrollment, $toEnrollment)) {
                    return $exchange->perform();
                }

                // Otherwise, we create a new exchange between both enrollments
                // so the user that owns the target enrollment can confirm the
                // exchange and allow the other user to enroll on the shift.
                $exchange = Exchange::make();
                $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
                $exchange->save();

                return $exchange;
            });

            $message = 'The exchange was successfully proposed.';
            if ($exchange->isPerformed()) {
                $message = 'The exchanged was successfully confirmed, since it matched an existing one.';
                event(new ExchangeWasConfirmed($exchange));
            }

            flash($message)->success();
        } catch (EnrollmentCannotBeExchangedException | ExchangeEnrollmentsOnDifferentCoursesException $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('dashboard');
    }
}
