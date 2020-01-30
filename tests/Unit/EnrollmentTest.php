<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    public function testEnrollmentsExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $fromShiftId = $fromEnrollment->shift_id;
        $toShiftId = $toEnrollment->shift_id;

        // Execute
        $actualReturn = $fromEnrollment->exchange($toEnrollment);

        // Assert
        $this->assertSame($actualReturn, $fromEnrollment);
        $actualFromEnrollment = Enrollment::find($fromEnrollment->id);
        $actualToEnrollment = Enrollment::find($toEnrollment->id);
        $this->assertEquals($fromShiftId, $actualToEnrollment->shift_id);
        $this->assertEquals($toShiftId, $actualFromEnrollment->shift_id);
    }

    public function testOrderByCourse()
    {
        // Prepare
        $courses = factory(Course::class, 10)->create();
        $courses->each(function ($course) {
            factory(Enrollment::class)->create([
                'course_id' => $course->id,
                'shift_id' => null,
            ]);
        });

        // Execute
        $actualReturn = Enrollment::orderByCourse()->get();

        // Assert
        $expectedOrder = Course::orderBy('year', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        $this->assertEquals($expectedOrder->pluck('id'), $actualReturn->pluck('course.id'));
    }

    public function testSimilarEnrollments()
    {
        // Prepare
        factory(Course::class, 2)->create()->each(function ($course) {
            $shift = factory(Shift::class)->create(['course_id' => $course->id]);
            factory(Enrollment::class)->create([
                'course_id' => $course->id,
                'shift_id' => $shift->id,
            ]);
        });
        $course = Course::first();
        $enrollment = factory(Enrollment::class)->create([
            'course_id' => $course->id,
            'shift_id' => $course->shifts()->first()->id,
        ]);

        // Execute
        $actualReturn = Enrollment::similarEnrollments($enrollment)->get();

        // Assert
        $expectedEnrollments = Enrollment::where('course_id', $course->id)
            ->where('shift_id', '!=', $course->shifts()->first()->id)
            ->where('id', '!=', $enrollment->id)
            ->get();

        $this->assertEquals($expectedEnrollments->pluck('id'), $actualReturn->pluck('id'));
    }

    public function testOrderByStudent()
    {
        // Prepare
        $course = factory(Course::class)->create();
        factory(Enrollment::class, 20)->create(['course_id' => $course->id]);

        // Execute
        $actualReturn = Enrollment::orderByStudent()->get();

        // Assert
        $expectedOrder = Student::orderBy('student_number')->get();

        $this->assertEquals($expectedOrder->pluck('id'), $actualReturn->pluck('student.id'));
    }

    public function testThrowsExceptionWhenStudentIsAlreadyEnrolledInCourse()
    {
        $this->expectException(\App\Exceptions\UserIsAlreadyEnrolledInCourseException::class);

        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Execute
        $student->enroll($course);
    }

    public function testIsDeletable()
    {
        // Prepare
        $enrollment = factory(Enrollment::class)->create([
            'shift_id' => null,
        ]);

        $enrollmentNotDeletable = factory(Enrollment::class)->create();

        // Execute
        $deletableReturn = $enrollment->isDeletable();
        $notDeletableReturn = $enrollmentNotDeletable->isDeletable();

        // Assert
        $this->assertTrue($deletableReturn);
        $this->assertFalse($notDeletableReturn);
    }
}
