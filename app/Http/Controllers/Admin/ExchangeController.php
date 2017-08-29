<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Judite\Contracts\Registry\ExchangeRegistry;

class ExchangeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.admin');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ExchangeRegistry $exchangeLogger)
    {
        $history = DB::transaction(function () use ($exchangeLogger) {
            return $exchangeLogger->paginate();
        });

        return view('exchanges.index', compact('history'));
    }
}
