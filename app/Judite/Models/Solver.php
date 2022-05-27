<?php

namespace App\Judite\Models;


use Illuminate\Support\Collection;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class solver extends Model
{
	/**
	 * changes the exchange format to the one used by the solver
	 *
	 * @param Course								$course
	 */
	public static function SolveAutomicExchangesOfCourse(Course $course)
	{
        $client = new Client();
		$url = env('SOLVER_URL','10.0.2.2').":".env('SOLVER_PORT','4567');
        $response = $client->request('POST',$url,['body'=>json_encode(['exchange_requests' => Solver::changeExchangeToSolverType($course->automaticExchanges())])]);
		$exchanges_ids = json_decode($response->getBody(), true)["solved_exchanges"];
        foreach ($exchanges_ids as $exchange_id){
            Exchange::Find($exchange_id)->perform();
        }
        return $exchanges_ids;
	}

	/**
	 * changes the exchange format to the one used by the solver
	 *
	 * @param \Illuminate\Database\Eloquent\Builder $query
	 *
	 * @return array
	 */
	private static function changeExchangeToSolverType($query){
        $values = collect();
        $query->each(function ($exchange) use ($values) {
            $values->push(array(
                'id' => $exchange->id,
                'from_shift_id' => $exchange->fromShift()->tag,
                'to_shift_id' => $exchange->toShift()->tag,
                'created_at' => $exchange->updated_at->timestamp
            ));
        });
        return $values;
	}


}
