<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Judite\Models\Course;
use Illuminate\Support\Facades\Mail;
use App\Judite\Models\Student;

class GroupController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
        $this->middleware('can.group')->only(['create', 'store']);
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

        return view('groups.index', compact('student', 'pendingGroups', 'confirmedGroups'));
    }

    /**
     * 
     */
    public function store(Request $request) {
        // Validate request
        $atributtes = $request->validate([]);

        // New Group
        DB::transaction(function () use ($atributtes) {
            // Get course and student
            $me = Auth::student();
            $course = Course::findOrFail($atributtes['course']);
            
            // Create group with first member
            $group = new Group();
            $group->addMember($me);
            $me->confirmGroup($group);
            
            // Save group to course
            $course->addGroup($group);
            $group->refresh(); // probably not necessary

            // Add remaining members and broadcast event
            foreach($atributtes['member'] as $memberId) {
                $member = Student::findOrFail($memberId);
                $group->addMember($member);
                Mail::to($member->user())->send(new ...);
            }

            return $group;
        });

        flash()->success('New group created successfully!');

        return redirect()->route('groups.index');
    }
}
