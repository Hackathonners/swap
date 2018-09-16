<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use App\Judite\Models\Invitation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Invitation\UpdateRequest;
use App\Exceptions\UserHasAlreadyAnInviteInGroupException;

class InvitationController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('student.verified');
        $this->middleware('can.group');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index($courseId)
    {
        $invitations = DB::transaction(function () use ($courseId) {
            return Invitation::with('group.memberships.student.user')
                ->where([
                    ['student_number', Auth::student()->student_number],
                    ['course_id', $courseId],
                ])
                ->get();
        });

        return view('invitations.index', compact('invitations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\Invitation\UpdateRequest $request
     * @param $groupId
     * @param $courseId
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UpdateRequest $request, $groupId, $courseId)
    {
        $studentNumber = $request->input('student_number');

        if ($studentNumber == Auth::student()->student_number) {
            flash('You can not invite yourself.')->error();

            return redirect()->back();
        }

        $invitation = new Invitation();
        $invitation->student_number = $studentNumber;
        $invitation->group_id = $groupId;
        $invitation->course_id = $courseId;

        try {
            $invitation->create($studentNumber, $groupId, $courseId);
            flash('Invitation successfully sent.')->success();
        } catch (UserHasAlreadyAnInviteInGroupException $e) {
            flash($e->getMessage())->error();
        }

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
        $courseId = 0;

        $remainingInvitations = DB::transaction(function () use ($id, &$courseId) {
            $invitation = Invitation::whereId($id)->first();

            $courseId = $invitation->course_id;
            $studentNumber = $invitation->student_number;

            $invitation->delete();

            return Invitation::with('group.memberships.student.user')
                ->where([
                    ['student_number', $studentNumber],
                    ['course_id', $courseId],
                ])
                ->count();
        });

        flash('Invitation destroyed.')->success();

        if ($remainingInvitations > 0) {
            return redirect()->back();
        } else {
            return redirect()->route('groups.show', compact('courseId'));
        }
    }
}
