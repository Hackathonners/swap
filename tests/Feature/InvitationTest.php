<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvitationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->enableGroupCreationPeriod();
    }

    /** @test */
    public function a_student_can_access_invitations_list()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);
        factory(Invitation::class)->create([
            'student_number' => $student->student_number,
            'course_id' => $course->id,
        ]);

        $response = $this->actingAs($student->user)
            ->get(route('invitations.index', $course->id));

        $response->assertStatus(200);
        $response->assertViewHas('invitations');
    }

    /** @test */
    public function a_student_in_a_group_can_create_valid_invitations()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);
        $group = factory(Group::class)->create([
            'course_id' => $course->id,
        ]);

        $student->join($group);

        $studentTwo = factory(Student::class)->create();
        $requestData = ['student_number' => $studentTwo->student_number];

        $response = $this->actingAs($student->user)
            ->post(route('invitations.store', [
                    'groupId' => $group->id,
                    'courseId' => $course->id,
                ]),
                $requestData);

        $response->assertRedirect();
        $this->assertEquals(1, Invitation::count());

        // Cannot invite the same student twice
        $requestData = ['student_number' => $studentTwo->student_number];

        $response = $this->actingAs($student->user)
            ->post(route('invitations.store', [
                    'groupId' => $group->id,
                    'courseId' => $course->id,
                ]),
                $requestData);

        $response->assertRedirect();
        $this->assertEquals(1, Invitation::count());
    }

    /** @test */
    public function a_student_in_a_group_cannot_invite_himslef()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);
        $group = factory(Group::class)->create([
            'course_id' => $course->id,
        ]);

        $student->join($group);

        $requestData = ['student_number' => $student->student_number];

        $response = $this->actingAs($student->user)
            ->post(route('invitations.store', [
                    'groupId' => $group->id,
                    'courseId' => $course->id,
                ]),
                $requestData);

        $this->assertEquals(0, Invitation::count());
    }

    /** @test */
    public function a_student_delete_his_invitations()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $invitation = factory(Invitation::class)->create([
            'student_number' => $student->student_number,
            'course_id' => $course->id,
        ]);

        $this->assertEquals(1, Invitation::count());

        $response = $this->actingAs($student->user)
            ->get(route('invitations.destroy', $invitation->id));

        $this->assertEquals(0, Invitation::count());
    }
}
