<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;
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

            return redirect()->route('home');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param int                                       $enrollmentId
     * @param \App\Http\Requests\Exchange\CreateRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store($enrollmentId, CreateRequest $request)
    {
        try {
            DB::transaction(function () use ($enrollmentId, $request) {
                $this->validate($request, [
                    'to_enrollment_id' => 'exists:enrollments,id',
                ]);

                $fromEnrollment = Enrollment::ownedBy(auth()->user()->student)->findOrFail($enrollmentId);
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

            flash('The exchange was successfully proposed.')->success();
        } catch (EnrollmentCannotBeExchangedException | ExchangeEnrollmentsOnDifferentCoursesException $e) {
            flash($e->getMessage())->error();
        }

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $exchange = Exchange::ownedBy(auth()->user()->student)
                ->findOrFail($id);

            $exchange->delete();

            return $exchange;
        });

        flash('The shift exchange request was successfully deleted.')->success();

        return redirect()->back();
    }
}
