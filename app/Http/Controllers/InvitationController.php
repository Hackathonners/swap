<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use Illuminate\Http\Request;
use App\Judite\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

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
     * @return \Illuminate\Http\Response
     */
    public function index($courseId)
    {
        $invitations = Invitation::where([
            ['student_number', '=', Auth::student()->student_number],
            ['course_id', '=', $courseId],
        ])->get();

        foreach ($invitations as $invitation) {
            $group = Group::whereId($invitation->group_id)->first();
            $memberships = $group->memberships()->get();

            $students = [];
            foreach ($memberships as $membershipKey => $membership) {
                $student = $membership->student()->first();
                $student->name = $student->user()->first()->name;
                array_push($students, $student);
            }
            $invitation->students = $students;
        }

        return view('invitations.index', compact('invitations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $groupId
     * @param $courseId
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $groupId, $courseId)
    {
        $studentNumber = $request->input('student_number');

        if ($studentNumber == Auth::student()->student_number) {
            flash('You can not invite yourself.')
                ->error();

            return redirect()->back();
        }

        $invitation = new Invitation();
        $invitation->student_number = $studentNumber;
        $invitation->group_id = $groupId;
        $invitation->course_id = $courseId;

        try {
            $invitation->save();
            flash('Invitation successfully sent.')
                ->success();
        } catch (QueryException $e) {
            flash('User already invited.')
                ->error();
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy($id)
    {
        $invitation = Invitation::whereId($id)->first();

        $courseId = $invitation->course_id;

        $invitation->delete();

        return redirect()->route('groups.show', compact('courseId'));
    }
}
