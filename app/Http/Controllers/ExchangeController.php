<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use Illuminate\Support\Facades\DB;
use App\Events\ExchangeWasDeclined;
use App\Events\ExchangeWasConfirmed;

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
            $exchange = student()->proposedExchanges()->findOrFail($id);

            return $exchange->perform();
        });

        event(new ExchangeWasConfirmed($exchange));
        flash('The shift exchange request was successfully confirmed.')->success();

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
            $exchange = student()->proposedExchanges()->findOrFail($id);
            $exchange->delete();

            return $exchange;
        });

        event(new ExchangeWasDeclined($exchange));
        flash('The shift exchange request was successfully declined.')->success();

        return redirect()->back();
    }
}
