<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function a_student_can_access_courses_list()
    {
        $student = factory(Student::class)->create();
        factory(Course::class, 20)->create();

        $response = $this->actingAs($student->user)
            ->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewHas('courses');
    }

    /** @test */
    public function unauthenticated_users_may_not_access_to_courses_list()
    {
        $response = $this->get(route('courses.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_admin_can_see_students_enrolled_in_a_course()
    {
        $admin = factory(User::class)->states('admin')->create();
        $course = factory(Course::class, 20)->create()->first();

        $response = $this->actingAs($admin)
            ->get(route('students.index', $course->id));

        $response->assertStatus(200);
        $response->assertViewHas('enrollments');
    }

    /** @test */
    public function students_may_not_see_students_enrolled_in_a_course()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class, 20)->create()->first();

        $response = $this->actingAs($student->user)
            ->get(route('students.index', $course->id));

        $response->assertStatus(404);
    }
}
