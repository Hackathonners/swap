<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class StudentTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentEnrollsInCourse()
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

    public function testStudentIsEnrolledInCourse()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        // Execute
        $actualReturn = $student->isEnrolledInCourse($course);

        // Assert
        $this->assertTrue($actualReturn);
    }
}
