<?php

namespace App\Imports;

use Maatwebsite\Excel\Row;
use App\Judite\Models\User;
use Illuminate\Support\Str;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use App\Judite\Models\Shift;
use Maatwebsite\Excel\Concerns\OnEachRow;
use App\Exceptions\InvalidFieldValueException;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EnrollmentsImport implements OnEachRow, WithHeadingRow
{
    /**
     * The enrollment row to import.
     *
     * @param \Maatwebsite\Excel\Row $row
     */
    public function onRow(Row $row)
    {
        $index = $row->getIndex();
        $row = $row->toArray();

        $student = Student::whereNumber($row['student_id'])
            ->firstOrNew(['student_number' => strtolower($row['student_id'])]);

        if (! $student->exists) {
            /** @var \App\Judite\Models\User $user */
            $user = User::make([
                'name' => $row['student_name'] ?? $row['student_id'],
                'email' => strtolower($row['student_email'] ?? $row['student_id'].'@alunos.uminho.pt'),
                'password' => bcrypt(Str::random(8)),
            ]);
            $user->verification_token = Str::random(32);
            $user->save();
            $student = $user->student()->save($student);
        }

        // Check if the given course id exists
        $course = Course::whereCode($row['course_id'])->first();
        if ($course === null) {
            $exception = new InvalidFieldValueException();
            $exception->setField('Course ID', $row['course_id'], "The course {$row['course_id']} does not exist. (at line {$index})");
            throw $exception;
        }

        // Check if the enrollment exists
        $enrollment = Enrollment::where([
            'course_id' => $course->id,
            'student_id' => $student->id,
        ])->first();

        if ($enrollment === null) {
            $enrollment = $student->enroll($course);
        }

        // Check if the given shift tag exists in the associated course
        if ($row['shift'] !== null) {
            $shift = $course->getShiftByTag($row['shift']);

            if ($shift === null) {
                $shift = Shift::make(['tag' => $row['shift']]);
                $course->addShift($shift);
            }

            $enrollment->shift()->associate($shift);
        } else {
            $enrollment->shift()->dissociate();
        }

        // Add the shift to the enrollment
        $enrollment->save();
    }
}
