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

    public function testListCourses()
    {
        // Prepare
        $student = factory(Student::class)->create();
        factory(Course::class, 20)->create();

        // Execute
        $response = $this->actingAs($student->user)
                         ->get(route('courses.index'));

        // Assert
        $response->assertStatus(200);
        $courses = Course::orderedList()->get();
        $expectedOrderedCourses = $courses->groupBy(function ($course) {
            return $course->present()->getOrdinalYear();
        });
        $response->assertViewHas('courses', $expectedOrderedCourses);
    }

    public function testRedirectToLoginWhenUnauthenticatedUsersAccessToCoursesList()
    {
        // Execute
        $response = $this->get(route('courses.index'));

        // Assert
        $response->assertRedirect(route('login'));
    }

    public function testListStudentsEnrolledInCourse()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $course = factory(Course::class, 20)->create()->first();

        // Execute
        $response = $this->actingAs($admin)
                         ->get(route('students.index', $course->id));

        // Assert
        $response->assertStatus(200);
        $response->assertViewHas('enrollments');
    }

    public function testStudentCannotListStudentsEnrolledInCourse()
    {
        // Prepare
        $user = factory(User::class)->create();
        $course = factory(Course::class, 20)->create()->first();

        // Execute
        $response = $this->actingAs($user)
                         ->get(route('students.index', $course->id));

        // Assert
        $response->assertStatus(403);
    }
}
