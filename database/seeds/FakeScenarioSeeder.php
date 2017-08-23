<?php

use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Database\Seeder;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Collection;

class FakeScenarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::transaction(function () {
            $numberOfStudents = 20;
            $numberOfShiftsPerCourse = 5;
            $numberOfRequestedExchangesPerStudent = 4;
            $numberOfEnrollmentsPerStudent = $numberOfRequestedExchangesPerStudent + 2;

            // Create students and shifts on courses.
            $courses = Course::all();
            $students = factory(Student::class, $numberOfStudents)->create();
            $courses->each(function ($course) use ($numberOfShiftsPerCourse) {
                $shifts = Collection::times($numberOfShiftsPerCourse, function ($number) use ($course) {
                    return factory(Shift::class)->make([
                        'course_id' => $course->id,
                        'tag' => "TP${number}",
                    ]);
                });
                $course->shifts()->saveMany($shifts);
            });

            // Create enrollments on random chosen courses.
            $students->each(function ($student) use ($courses, $numberOfEnrollmentsPerStudent) {
                $courses = $courses->shuffle();
                $enrollments = Collection::times($numberOfEnrollmentsPerStudent, function ($number) use ($courses) {
                    $course = $courses->get($number);

                    return factory(Enrollment::class)->make([
                        'course_id' => $course->id,
                        'shift_id' => $course->shifts()->inRandomOrder()->first()->id,
                    ]);
                });
                $student->enrollments()->saveMany($enrollments);
            });

            // Create exchange pairs between students.
            $students->each(function ($student) use ($numberOfRequestedExchangesPerStudent) {
                $enrollments = $student->enrollments()->with('course')->get()->shuffle();

                for ($i = $numberOfRequestedExchangesPerStudent; $i > 0 && ! $enrollments->isEmpty(); $i--) {
                    $enrollment = $enrollments->shift();

                    $matchingEnrollment = Enrollment::where('course_id', $enrollment->course->id)
                        ->where('shift_id', '!=', $enrollment->shift->id)
                        ->where('id', '!=', $enrollment->id)
                        ->inRandomOrder()
                        ->first();

                    if (! is_null($matchingEnrollment)) {
                        Exchange::create([
                            'from_enrollment_id' => $enrollment->id,
                            'to_enrollment_id' => $matchingEnrollment->id,
                        ]);
                    }
                }
            });
        });
    }
}
