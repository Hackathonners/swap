<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->enableGroupCreationPeriod();
    }

    /** @test */
    public function a_student_can_access_courses_with_groups_list()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);

        factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($student->user)
            ->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertViewHas('enrollments');
    }

    /** @test */
    public function unauthenticated_users_may_not_access_courses_with_groups_list()
    {
        $response = $this->get(route('groups.index'));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function an_admin_can_see_a_course_groups()
    {
        $admin = factory(User::class)->states('admin')->create();
        $course = factory(Course::class, 20)->create([
            'group_min' => 3,
            'group_max' => 3,
        ])->first();

        $response = $this->actingAs($admin)
            ->get(route('admin.groups.show', $course->id));

        $response->assertStatus(200);
        $response->assertViewHas('groups');
    }
}
