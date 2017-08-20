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
    public function a_student_can_access_to_the_courses_list()
    {
        // Prepare
        $student = factory(Student::class)->create();
        factory(Course::class, 20)->create();

        // Execute
        $this->actingAs($student->user);
        $response = $this->get(route('courses.index'));

        // Assert
        $response->assertStatus(200);
        $courses = Course::orderedList()->get();
        $expectedOrderedCourses = $courses->groupBy(function ($course) {
            return $course->present()->getOrdinalYear();
        });
        $response->assertViewHas('courses', $expectedOrderedCourses);
    }

    /** @test */
    public function unauthenticated_users_may_not_access_to_courses_list()
    {
        // Execute
        $response = $this->get(route('courses.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_admin_can_see_students_enrolled_in_a_course()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $course = factory(Course::class, 20)->create()->first();

        // Execute
        $this->actingAs($admin);
        $response = $this->get(route('students.index', $course->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('enrollments');
    }

    /** @test */
    public function students_may_not_see_students_enrolled_in_a_course()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class, 20)->create()->first();

        // Execute
        $this->actingAs($student->user);
        $response = $this->get(route('students.index', $course->id));

        // Assert
        $response->assertStatus(302); // TODO: effetive unauthorized exception handling
    }
}
