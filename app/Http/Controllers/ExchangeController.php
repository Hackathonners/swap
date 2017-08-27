<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeclinedExchangeNotification;
use App\Mail\ConfirmedExchangeNotification;
use App\Http\Requests\Exchange\CreateRequest;
use App\Exceptions\MultipleEnrollmentExchangesException;
use App\Exceptions\ExchangeEnrollmentWithoutShiftException;
use App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException;

class ExchangeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('can.exchange');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param int $enrollmentId
     *
     * @return \Illuminate\Http\Response
     */
    public function create($enrollmentId)
    {
        try {
            $data = DB::transaction(function () use ($enrollmentId) {
                $enrollment = Enrollment::ownedBy(auth()->user()->student)
                    ->findOrFail($enrollmentId);

                $this->authorize('exchange', $enrollment);

                if (is_null($enrollment->shift)) {
                    throw new \LogicException('Enrollments without associated shifts cannot be exchanged.');
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

            return redirect()->route('home');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Exchange\CreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $this->validate($request, [
                    'from_enrollment_id' => 'exists:enrollments,id',
                    'to_enrollment_id' => 'exists:enrollments,id',
                ]);

                $fromEnrollment = Enrollment::find($request->input('from_enrollment_id'));
                $toEnrollment = Enrollment::find($request->input('to_enrollment_id'));
                $this->authorize('exchange', $fromEnrollment);

                // Firstly check if the inverse exchange for the same enrollments
                // already exists. If the inverse record is found then we will
                // exchange and update both enrollments of this exchange.
                $exchange = Exchange::findMatchingExchange($fromEnrollment, $toEnrollment);
                if (! is_null($exchange)) {
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

            flash('The exchange was successfully proposed.')->success();
        } catch (MultipleEnrollmentExchangesException
        | ExchangeEnrollmentsOnDifferentCoursesException
        | ExchangeEnrollmentWithoutShiftException $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('home');
    }

    /**
     * Store a confirmation of an exchange in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeConfirmation(Request $request)
    {
        $exchange = DB::transaction(function () use ($request) {
            $this->validate($request, [
                'exchange_id' => 'exists:exchanges,id',
            ]);

            $exchange = Exchange::find($request->input('exchange_id'));
            $this->authorize('decide', $exchange);

            return $exchange->perform();
        });

        flash('The shift exchange request was successfully confirmed.')->success();
        Mail::to($exchange->fromStudent()->user)
            ->send(new ConfirmedExchangeNotification($exchange));

        return redirect()->back();
    }

    /**
     * Store a decline of an exchange in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function storeDecline(Request $request)
    {
        $exchange = DB::transaction(function () use ($request) {
            $this->validate($request, [
                'exchange_id' => 'exists:exchanges,id',
            ]);

            $exchange = Exchange::find($request->input('exchange_id'));
            $this->authorize('decide', $exchange);

            $exchange->delete();

            return $exchange;
        });

        flash('The shift exchange request was successfully declined.')->success();
        Mail::to($exchange->fromStudent()->user)
            ->send(new DeclinedExchangeNotification($exchange));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        DB::transaction(function () use ($request) {
            $this->validate($request, [
                'exchange_id' => 'exists:exchanges,id',
            ]);

            $exchange = Exchange::find($request->input('exchange_id'));
            $this->authorize('delete', $exchange);

            $exchange->delete();

            return $exchange;
        });

        flash('The shift exchange request was successfully deleted.')->success();

        return redirect()->back();
    }
}
