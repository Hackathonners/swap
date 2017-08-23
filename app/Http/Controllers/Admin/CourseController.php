<?php

namespace App\Http\Controllers\Admin;

use App\Judite\Models\Course;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
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
            $data = [];
            $data['course'] = Course::findOrFail($id);
            $data['enrollments'] = $data['course']->enrollments()
                ->with('student.user')
                ->orderByStudent()
                ->paginate();

            return $data;
        });

        return view('students.index', $data);
    }
}
