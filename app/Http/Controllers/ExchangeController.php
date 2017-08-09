<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use App\Mail\DeclinedExchangeNotification;
use App\Mail\ConfirmedExchangeNotification;
use App\Http\Requests\Exchange\CreateRequest;

class ExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Requests\Exchange\CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $exchange = DB::transaction(function () use ($request) {
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

        return $exchange;
    }

    /**
     * Store a confirmation of an exchange in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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

        flash('The shift exchange request has been confirmed. A notification will be sent to the student that proposed this exchange.')->success();
        Mail::to($exchange->fromStudent()->user)
            ->send(new ConfirmedExchangeNotification($exchange));

        return redirect()->back();
    }

    /**
     * Store a decline of an exchange in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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

        flash('The shift exchange request has been declined. A notification will be sent to the student that proposed this exchange.')->success();
        Mail::to($exchange->fromStudent()->user)
            ->send(new DeclinedExchangeNotification($exchange));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $exchange = DB::transaction(function () use ($request) {
            $this->validate($request, [
                'exchange_id' => 'exists:exchanges,id',
            ]);

            $exchange = Exchange::find($request->input('exchange_id'));
            $this->authorize('delete', $exchange);

            $exchange->delete();

            return $exchange;
        });

        return ['status' => 'The exchange has been deleted.'];
    }
}
