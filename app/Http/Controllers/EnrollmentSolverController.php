<?php

namespace App\Http\Controllers;

use App\Judite\Models\Shift;
use Illuminate\Http\Request;
use App\Judite\Models\Exchange;
use Illuminate\Support\Facades\DB;
use App\Events\ExchangeWasConfirmed;
use Illuminate\Support\Facades\Auth;
use App\Judite\Models\ExchangeQueueEntry;
use App\Judite\Services\SwapSolverService;
use App\Judite\Models\ExchangeRegistryEntry;

class EnrollmentSolverController extends Controller
{
    private $solver;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
        $this->middleware('can.exchange')->except('destroy');

        $this->solver = resolve(SwapSolverService::class);
    }

    public function store($id, Request $request)
    {
        try {
            DB::transaction(function () use ($id, $request) {
                $this->validate($request, [
                    'to_shift_id' => 'exists:shifts,id',
                ]);

                $student = Auth::student();
                $fromEnrollment = $student->enrollments()->findOrFail($id);
                $fromShift = $fromEnrollment->shift;
                $toShift = Shift::find($request->input('to_shift_id'));

                $exchangeQueue = ExchangeQueueEntry::make();
                $exchangeQueue->fromShiftRelation()->associate($fromShift);
                $exchangeQueue->toShiftRelation()->associate($toShift);
                $exchangeQueue->fromStudentRelation()->associate($student);
                $exchangeQueue->fromEnrollmentRelation()->associate($fromEnrollment);
                $exchangeQueue->save();

                $exchanges = ExchangeQueueEntry::all()->map(function ($item) {
                    return $item->toServiceFormat();
                });

                $response = $this->solver->getExchangesMatches($exchanges);

                $matches = json_decode($response->getBody());
                $numberOfSolvedExchanges = count($matches->solved_exchanges);

                if ($numberOfSolvedExchanges == 0) {
                    flash('No match found yet.');
                    return;
                }

                $transactionId = ExchangeRegistryEntry::max('transaction_id') + 1;
                $exchanges = collect([]);

                for ($i=0; $i < $numberOfSolvedExchanges - 1; $i++) {
                    $entry = ExchangeQueueEntry::findOrFail($matches->solved_exchanges[$i]);
                    $fromEnrollment = $entry->fromEnrollment();

                    $entry = ExchangeQueueEntry::findOrFail($matches->solved_exchanges[$i+1]);
                    $toEnrollment = $entry->fromEnrollment();

                    $exchange = Exchange::make();
                    $exchange->setAutomaticExchangeEnrollments($fromEnrollment, $toEnrollment);
                    $exchange->save();

                    $exchange = $exchange->perform($transactionId);
                    $exchanges->push($exchange);
                }

                ExchangeQueueEntry::destroy(array_map(
                    function ($e) { return (int) $e; },
                    $matches->solved_exchanges
                ));

                flash()->success('A match has found! The exchanged was automatically performed.');

                $exchanges->each(function ($exchange) {
                    if ($exchange->isPerformed()) {
                        event(new ExchangeWasConfirmed($exchange));
                    }
                });

                return $exchanges;
            });
        } catch (\Exception $e) {
            flash($e->getMessage())->error();
            throw $e;
        }

        return redirect()->route('dashboard');
    }

    public function destroy($id) {
        $result = DB::transaction(function () use ($id) {
            return ExchangeQueueEntry::destroy($id);
        });

        if ($result) {
            flash()->success('Service exchange request was removed');
        }

        return redirect()->route('dashboard');
    }
}
