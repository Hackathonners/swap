<?php

namespace Tests\Feature;

use Tests\TestCase;
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
}
