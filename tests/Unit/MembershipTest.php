<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Student;
use App\Judite\Models\Membership;

class MembershipTest extends TestCase
{
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
}
