<?php

use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Database\Seeder;
use App\Judite\Models\Enrollment;

class DummyEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberOfStudents = 30;
        $numberOfCourses = 6;
        $numberOfShifts = 5;

        // Create students, courses and shifts
        $students = factory(Student::class, $numberOfStudents)->create();
        $courses = factory(Course::class, $numberOfCourses)->create();
        $courses->each(function ($course) use ($numberOfShifts) {
            factory(Shift::class, $numberOfShifts)->create(['course_id' => $course->id]);
        });

        $students->each(function ($student) use ($courses) {
            $courses->each(function ($course) use ($student) {
                factory(Enrollment::class)->create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'shift_id' => $course->shifts->random()->id,
                ]);
            });
        });

        // Create exchange pairs
        $student = factory(Student::class)->create();
        $anotherStudent = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $shift = factory(Shift::class)->create(['course_id' => $course->id]);
        $anotherShift = factory(Shift::class)->create(['course_id' => $course->id]);

        $enrollment = Enrollment::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'shift_id' => $shift->id,
        ]);

        $anotherEnrollment = Enrollment::create([
            'student_id' => $anotherStudent->id,
            'course_id' => $course->id,
            'shift_id' => $anotherShift->id,
        ]);

        Exchange::create([
            'from_enrollment_id' => $enrollment->id,
            'to_enrollment_id' => $anotherEnrollment->id,
        ]);
    }
}
