<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Judite\Contracts\ExchangeLogger;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class ExchangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExchangeLogger $exchangeLogger)
    {
        $history = DB::transaction(function () use ($exchangeLogger) {
            return $exchangeLogger->history();
        });

        return view('exchanges.index', compact('history'));
    }
}
