<?php

namespace App\Http\Controllers;

use App\Judite\Models\Group;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Judite\Models\Course;
use App\Judite\Models\User;
use Maatwebsite\Excel\Facades\Excel;
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
        #$this->middleware('can.student');
        #$this->middleware('can.group')->only(['create', 'store']);
        $this->middleware('student.verified');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $student = Auth::student();

        $pendingGroups = DB::transaction(function () use ($student) {
            return $student->pendingGroups()->get();
        });

        $confirmedGroups = DB::transaction(function () use ($student) {
            return $student->confirmedGroups()->get();
        });

        return view('groups.index', compact('student', 'pendingGroups', 'confirmedGroups'));
    }

    public function adminIndex()
    {

        $groups = DB::table('groups')->get();

        $courses = DB::transaction(function () {
            return Course::orderedList()->get();
        });

        return view('groups.admin', compact('groups', 'courses'));
    }

    public function create()
    {
        $student = Auth::student();

        $courses = $student->getCoursesWithoutGroup();

        return view('groups.create', compact('courses'));
    }

    /**
     * 
     */
    public function store(Request $request) {
        
        $atributtes = $request->validate([
            'email' => 'required',
            'course_id' => 'required'
        ]);

        DB::transaction(function () use ($atributtes){
            $creator = Auth::student();
            $course = Course::findOrFail($atributtes['course_id']);

            $group = new Group();
            $course->addGroup($group);
            $group->refresh();

            $group->addMember($creator);
            $creator->confirmGroup($group);

            $user = User::where('email', $atributtes['email'])->first();
            $studentToAdd = $user->student()->first();
            $group->addMember($studentToAdd);

            return $group;

        });

        flash()->success('New group created successfully!');

        return redirect()->route('groups.index');

    }

    public function edit(Group $group) {
        return view('groups.edit', compact('group'));
    }

    public function sendInvite(Group $group, Request $request) {
        $atributtes = $request->validate([
            'email' => 'required',
        ]);
        $user = User::where('email', $atributtes['email'])->first();
        $studentToAdd = $user->student()->first();
        $group->addMember($studentToAdd);

        flash()->success('Invite sent successfully!');
        return redirect()->route('groups.index');
    }

    public function update(Group $group) {
        $student = Auth::student();
        $student->confirmGroup($group);
        flash()->success('Invite accepted successfully!');
        return redirect()->route('groups.index');
    }

    public function destroy(Group $group) {
        $student = Auth::student();
        $student->declineGroup($group);
        return redirect()->route('groups.index');
    }

    public function leave(Group $group) {
        $student = Auth::student();
        $student->leaveGroup($group);
        return redirect()->route('groups.index');
    }

    /**
     * Exports the list of groups in each course.
     *
     * @return \Illuminate\Http\Response
     */
    public function export()
    {

        $groups = DB::transaction(function () {
            $groups = Group::with('course','students')
                ->get()
                ->sortByDesc('courses.name');

            return $groups;
        });

        $filename = "movies.json";
        $handle = fopen($filename, 'w+');
        fputs($handle, $groups->toJson(JSON_PRETTY_PRINT));
        fclose($handle);
        $headers = array('Content-type'=> 'application/json');
        return response()->download($filename,'groups.json',$headers);

    }

}
