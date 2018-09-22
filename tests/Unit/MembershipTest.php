<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Student;
use App\Judite\Models\Membership;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\UserHasAlreadyGroupInCourseException;

class MembershipTest extends TestCase
{
    use DatabaseTransactions;

    public function testOrderByStudent()
    {
        // Prepare
        $group = factory(Group::class)->create();
        factory(Membership::class, 20)->create(['group_id' => $group->id]);

        // Execute
        $actualReturn = Membership::orderByStudent()->get();

        // Assert
        $expectedOrder = Student::orderBy('student_number')->get();

        $this->assertEquals($expectedOrder->pluck('id'), $actualReturn->pluck('student.id'));
    }

    public function testStudentHasOnlyOneMembershipPerCourse()
    {
        $student = factory(Student::class)->create();
        $group = factory(Group::class)->create();

        $validMembership = $student->join($group);

        try {
            $invalidMembership = $student->join($group);

            $this->assertTrue(false);
        } catch (UserHasAlreadyGroupInCourseException $e) {
            $this->assertTrue(true);
        }
    }
}
