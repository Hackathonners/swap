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

    protected $student;
    protected $course;

    public function setUp()
    {
        parent::setUp();
        $this->student = factory(Student::class)->create();
        $this->course = factory(Course::class)->create();
    }

    /** @test */
    public function a_student_can_enroll_in_a_course()
    {
        // Execute
        $this->actingAs($student->user);
        $response = $this->post(route('enrollments.create'), ['course_id' => $course->id]);

        // Assert
        $response->assertRedirect(route('courses.index'));
        $this->assertEquals($course->id, $student->enrollments()->first()->course_id);
    }

    /** @test */
    public function a_student_may_not_enroll_in_a_course_multiple_times()
    {
        // Prepare
        factory(Enrollment::class)->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        // Execute
        $this->actingAs($this->student->user);
        $response = $this->post(route('enrollments.create'), ['course_id' => $this->course->id]);

        // Assert
        $response->assertRedirect(route('courses.index'));
        $this->assertEquals(1, Enrollment::count());
    }

    /** @test */
    public function students_may_not_export_enrollments()
    {
        // Execute
        $this->actingAs($this->student->user);
        $response = $this->get(route('enrollments.export'));

        // Assert
        $response->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_may_not_enroll_in_courses()
    {
        // Execute
        $response = $this->post(route('enrollments.create'), ['course_id' => $this->course->id]);

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertEquals(0, Enrollment::count());
    }
}
