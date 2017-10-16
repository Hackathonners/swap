<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use App\Events\ExchangeWasDeclined;
use App\Events\ExchangeWasConfirmed;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Exchange\CreateRequest;

class ExchangeController extends Controller
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
     * Store a confirmation of an exchange in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($id)
    {
        $exchange = DB::transaction(function () use ($id) {
            $exchange = Auth::student()->proposedExchanges()->findOrFail($id);

            return $exchange->perform();
        });
        flash('The shift exchange request was successfully confirmed.')->success();
        event(new ExchangeWasConfirmed($exchange));

        return redirect()->back();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  $request
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create(CreateRequest $request)
    {
        try {
            $id = $request->input('enrollment_id');
            $data = DB::transaction(function () use ($id) {
                $enrollment = Auth::student()->enrollments()->findOrFail($id);

                if (! $enrollment->availableForExchange()) {
                    throw new \LogicException('The enrollment is not available for exchange.');
                }

                $matchingEnrollments = Enrollment::similarEnrollments($enrollment)
                    ->orderByStudent()
                    ->get();

                $course = $enrollment->course;

                $shiftsAvailable = $course->shifts()
                    ->orderBy('tag')
                    ->get()
                    ->except([
                        'id' => $enrollment->shift->id,
                    ])
                    ->pluck('tag');

                return compact('enrollment', 'matchingEnrollments', 'shiftsAvailable');
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
     * Store a decline of an exchange in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decline($id)
    {
        $exchange = DB::transaction(function () use ($id) {
            $exchange = Auth::student()->proposedExchanges()->findOrFail($id);
            $exchange->delete();

            return $exchange;
        });
        flash('The shift exchange request was successfully declined.')->success();
        event(new ExchangeWasDeclined($exchange));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            Auth::student()->requestedExchanges()->findOrFail($id)->delete();
        });
        flash('The shift exchange request was successfully deleted.')->success();

        return redirect()->back();
    }
}
