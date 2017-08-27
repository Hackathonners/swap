<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeclinedExchangeNotification;
use App\Mail\ConfirmedExchangeNotification;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Store a confirmation of an exchange in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function confirm($id)
    {
        $exchange = DB::transaction(function () use ($id) {
            $exchange = Exchange::findOrFail($id);
            $this->checkDecideAuthorization($exchange);

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
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function decline($id)
    {
        $exchange = DB::transaction(function () use ($id) {
            $exchange = Exchange::findOrFail($id);
            $this->checkDecideAuthorization($exchange);
            $exchange->delete();

            return $exchange;
        });

        flash('The shift exchange request was successfully declined.')->success();
        Mail::to($exchange->fromStudent()->user)
            ->send(new DeclinedExchangeNotification($exchange));

        return redirect()->back();
    }

    /**
     * Checks authorization of the authenticated user to decide on the given exchange.
     *
     * @param \App\Judite\Models\Exchange $exchange
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function checkDecideAuthorization(Exchange $exchange)
    {
        // We are going to hide the third-party enrollments from user that
        // sent the request. If they try to confirm their own exchanges
        // a 403 will be returned, otherwise a 404 will be simulated.
        try {
            $this->authorize('decide', $exchange);
        } catch (AuthorizationException $e) {
            throw $exchange->isOwnedBy(auth()->user()->student)
                ? $e
                : new ModelNotFoundException(get_class($exchange), $exchange->id);
        }
    }
}
