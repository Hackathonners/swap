<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $student = DB::transaction(function () {
            return Auth::user()->student;
        });

        $pendingGroups = DB::transaction(function () use ($student) {
            return $student->pendingGroups()->get();
        });

        $confirmedGroups = DB::transaction(function () use ($student) {
            return $student->confirmedGroups()->get();
        });

        return view('groups.index', compact('pendingGroups', 'confirmedGroups'));
    }
}
