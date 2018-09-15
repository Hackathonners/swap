<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\Course\UpdateRequest;

class CourseController extends Controller
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
     * Display a resource of given id.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $data = DB::transaction(function () use ($id) {
            $course = Course::findOrFail($id);
            $enrollments = $course->enrollments()
                ->with('student.user')
                ->orderByStudent()
                ->paginate();

            return compact('course', 'enrollments');
        });

        return view('students.index', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\Course\UpdateRequest $request
     * @param int                                     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateRequest $request, $id)
    {
        DB::transaction(function () use ($request, $id) {
            $course = Course::findOrFail($id);

            $course->fill($request->all());
            $course->save();

            flash($course->name . ' groups size were successfully updated.')->success();
        });

        return redirect()->route('admin.groups.index');
    }
}
