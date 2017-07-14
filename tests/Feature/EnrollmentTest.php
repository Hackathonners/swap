<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentCanEnrollCourses()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();

        // Execute
        $response = $this->actingAs($student->user)
                         ->post(route('enrollments.create'), ['course_id' => $course->id]);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($course->id, $student->enrollments()->first()->course_id);
    }

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
        $this->actingAs($student->user)
             ->post(route('enrollments.create'), ['course_id' => $course->id]);
    }
}
