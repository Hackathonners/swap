<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GroupStudentController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.group');
        $this->middleware('can.student');
        $this->middleware('student.verified');
    }

    /**
     * Confirm joining a group.
     *
     * @param \App\Judite\Models\Group  $group
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Group $group)
    {
        $student = DB::transaction(function () {
            return Auth::user()->student;
        });

        $this->authorize('reply-group', [$student, $group]);

        DB::transaction(function () use ($student, $group) {
            return $student->confirmGroup($group);
        });
        flash('The group request was successfully confirmed.')->success();

        return redirect()->back();
    }

    /**
     * Decline joining a group.
     * 
     * @param \App\Judite\Models\Group  $group
     * 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function decline(Group $group)
    {
        $student = Auth::user()->student;

        $this->authorize('reply-group', [$student, $group]);

        DB::transaction(function () use ($student, $group) {
            return $student->declineGroup($group);
        });
        flash('The group request was successfully declined.')->success();

        return redirect()->back();
    }
}
