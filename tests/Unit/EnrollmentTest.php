<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentEnrollCourse()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        // Execute
        $actualReturn = $student->enroll($course);

        // Assert
        $this->assertEquals(Enrollment::class, get_class($actualReturn));
        $this->assertEquals(1, Enrollment::count());
        $enrollment = Enrollment::first();
        $this->assertEquals($student->id, $enrollment->student_id);
        $this->assertEquals($course->id, $enrollment->course_id);
    }

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

    /**
     * @expectedException App\Exceptions\UserIsAlreadyEnrolledInCourseException
     */
    public function testThrowsExceptionWhenStudentIsAlreadyEnrolledInCourse()
    {
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
}
