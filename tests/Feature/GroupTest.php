<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use App\Judite\Models\Invitation;
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
        $courses = factory(Course::class, 2)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);
        $group = factory(Group::class)->create([
            'course_id' => $courses[0]->id,
        ]);

        factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $courses[0]->id,
        ]);
        factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $courses[1]->id,
        ]);

        $student->join($group);

        $response = $this->actingAs($student->user)
            ->get(route('groups.index'));

        $response->assertStatus(200);
        $response->assertViewHas('enrollments');
    }

    /** @test */
    public function a_student_cannot_access_courses_without_groups_list()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 0,
            'group_max' => 0,
        ]);

        factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($student->user)
            ->get(route('groups.index'));

        $response->assertStatus(200);
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

    /** @test */
    public function an_admin_can_see_a_course_index_with_groups_size()
    {
        $admin = factory(User::class)->states('admin')->create();
        factory(Course::class, 20)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);

        $response = $this->actingAs($admin)
            ->get(route('admin.groups.index'));

        $response->assertStatus(200);
        $response->assertViewHas('courses');
    }

    /** @test */
    public function a_student_can_see_his_group_in_a_course()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class, 2)->create([
            'group_min' => 1,
            'group_max' => 1,
        ])->first();
        $group = factory(Group::class)->create([
            'course_id' => $course->id,
        ]);

        $student->join($group);

        $response = $this->actingAs($student->user)
            ->get(route('groups.show', $course->id));

        $response->assertStatus(200);
        $response->assertViewHas('membership');
    }

    /** @test */
    public function a_student_can_accept_a_group_invitation()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 2,
            'group_max' => 2,
        ]);
        $group = factory(Group::class)->create([
            'course_id' => $course->id,
        ]);

        $invitation = factory(Invitation::class)->create([
            'student_number' => $student->student_number,
            'course_id' => $course->id,
            'group_id' => $group->id,
        ]);

        $response = $this->actingAs($student->user)
            ->get(route('groups.update', $invitation->id));

        $this->assertEquals($group->id, $student->memberships()->first()
            ->group()->first()->id);
    }

    /** @test */
    public function a_student_cant_accept_invalid_invitation()
    {
        $invitationCount = Invitation::count();

        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $group = factory(Group::class)->create();

        $invitation = factory(Invitation::class)->create([
            'student_number' => factory(Student::class)->create()->student_number,
            'course_id' => $course->id,
            'group_id' => $group->id,
        ]);

        $this->actingAs($student->user)
            ->get(route('groups.update', $invitation->id));

        $this->assertEquals($invitationCount + 1, Invitation::count());
        $this->assertEquals(0, $student->memberships()->count());
    }
}
