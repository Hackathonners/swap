<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GroupTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        $this->enableGroupCreationPeriod();
        $this->student = factory(Student::class)->create();
        $this->course = factory(Course::class)->create([
            'group_min' => 3,
            'group_max' => 3,
        ]);
        $this->group = factory(Group::class)->create([
            'course_id' => $this->course->id,
        ]);
    }

    /** @test */
    public function a_student_can_create_one_group_in_a_course_with_valid_group_size()
    {
        $groupCount = Group::count();

        $this->actingAs($this->student->user)
            ->get(route('groups.store', -1));

        $this->assertEquals($groupCount, Group::count());

        $this->actingAs($this->student->user)
            ->get(route('groups.store', $this->course->id));

        $this->assertEquals($groupCount + 1, Group::count());
    }

    /** @test */
    public function a_student_cannot_create_two_group_in_a_course_with_valid_group_size()
    {
        $groupCount = Group::count();

        $this->student->join($this->group);

        $creationResponse = $this->actingAs($this->student->user)
            ->get(route('groups.store', $this->course->id));

        $this->assertEquals($groupCount, Group::count());
    }

    /** @test */
    public function a_student_can_delete_a_group_when_alone()
    {
        $groupCount = Group::count();
        $invitationCount = Invitation::count();

        $this->student->join($this->group);

        $numberOfInvitations = 5;
        factory(Invitation::class, $numberOfInvitations)->create([
            'course_id' => $this->course->id,
            'group_id' => $this->group->id,
        ]);

        $this->assertEquals($numberOfInvitations, Invitation::count());

        $deletionResponse = $this->actingAs($this->student->user)
            ->get(route('groups.destroy', $this->course->id));

        $this->assertEquals($groupCount - 1, Group::count());
        $this->assertEquals($invitationCount, Invitation::count());
    }

    /** @test */
    public function a_student_can_leave_a_group()
    {
        $groupCount = Group::count();
        $secondStudent = factory(Student::class)->create();

        $secondStudent->join($this->group);
        $this->student->join($this->group);

        $deletionResponse = $this->actingAs($this->student->user)
            ->get(route('groups.destroy', $this->course->id));

        $this->assertEquals($groupCount, Group::count());
    }

    /** @test */
    public function a_student_cant_accept_an_invitation_to_a_full_group()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create([
            'group_min' => 0,
            'group_max' => 0,
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

        $this->assertEquals(0, $student->memberships()->count());
    }

    /** @test */
    public function a_student_cant_accept_an_invitation_if_already_has_a_group_in_course()
    {
        $course = factory(Course::class)->create([
            'group_min' => 5,
            'group_max' => 5,
        ]);

        $student = factory(Student::class)->create();

        $groupOne = factory(Group::class)->create([
            'course_id' => $course->id,
        ]);
        $groupTwo = factory(Group::class)->create([
            'course_id' => $course->id,
        ]);

        $student->join($groupOne);

        $this->assertEquals($groupOne->id, $student->memberships()->first()
            ->group()->first()->id);
        $this->assertEquals(1, $student->memberships()->count());

        $invitation = factory(Invitation::class)->create([
            'student_number' => $student->student_number,
            'course_id' => $course->id,
            'group_id' => $groupTwo->id,
        ]);

        $response = $this->actingAs($student->user)
            ->get(route('groups.update', $invitation->id));

        $this->assertEquals($groupOne->id, $student->memberships()->first()
            ->group()->first()->id);
        $this->assertEquals(1, $student->memberships()->count());
    }
}
