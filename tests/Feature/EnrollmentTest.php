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

    public function setUp(): void
    {
        parent::setUp();
        $this->enableEnrollmentsPeriod();
        $this->student = factory(Student::class)->create();
        $this->course = factory(Course::class)->create();
    }

    /** @test */
    public function a_student_can_enroll_in_a_course()
    {
        $response = $this->actingAs($this->student->user)
            ->post(route('enrollments.store', $this->course->id));

        $response->assertRedirect(route('courses.index'));
        $this->assertEquals($this->course->id, $this->student->enrollments()->first()->course_id);
    }

    /** @test */
    public function a_student_can_delete_an_enrollment_in_a_course()
    {
        factory(Enrollment::class)->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
            'shift_id' => null,
        ]);

        $response = $this->actingAs($this->student->user)
            ->delete(route('enrollments.destroy', $this->course->id));

        $response->assertRedirect();
        $this->assertEquals(0, Enrollment::count());
    }

    /** @test */
    public function a_student_may_not_delete_an_enrollment_with_shift()
    {
        $enrollment = factory(Enrollment::class)->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->actingAs($this->student->user)
            ->delete(route('enrollments.destroy', $this->course->id));

        $response->assertRedirect();
        $this->assertTrue($enrollment->is(Enrollment::first()));
    }

    /** @test */
    public function a_student_may_not_delete_an_enrollment_of_another_student()
    {
        $enrollment = factory(Enrollment::class)->create([
            'course_id' => $this->course->id,
        ]);

        $response = $this->actingAs($this->student->user)
            ->delete(route('enrollments.destroy', $this->course->id));

        $response->assertRedirect();
        $this->assertTrue($enrollment->is(Enrollment::first()));
    }

    /** @test */
    public function a_student_may_not_enroll_in_a_course_multiple_times()
    {
        factory(Enrollment::class)->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->actingAs($this->student->user)
            ->post(route('enrollments.store', $this->course->id));

        $response->assertRedirect(route('courses.index'));
        $this->assertEquals(1, Enrollment::count());
    }

    /** @test */
    public function students_may_not_export_enrollments()
    {
        $response = $this->actingAs($this->student->user)
            ->get(route('enrollments.export'));

        $response->assertStatus(404);
    }

    /** @test */
    public function unauthenticated_users_may_not_enroll_in_courses()
    {
        $response = $this->post(route('enrollments.store', $this->course->id));

        $response->assertRedirect(route('login'));
        $this->assertEquals(0, Enrollment::count());
    }

    /** @test */
    public function unauthenticated_users_may_not_delete_an_enrollment()
    {
        $enrollment = factory(Enrollment::class)->create([
            'student_id' => $this->student->id,
            'course_id' => $this->course->id,
        ]);

        $response = $this->delete(route('enrollments.destroy', $this->course->id));

        $response->assertRedirect(route('login'));
        $this->assertTrue($enrollment->is(Enrollment::first()));
    }
}
