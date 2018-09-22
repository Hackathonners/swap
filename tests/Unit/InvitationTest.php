<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\UserHasAlreadyAnInviteInGroupException;

class InvitationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function testStudentHasOnlyOneInvitationToEachGroup()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $group = factory(Group::class)->create();

        Invitation::create($student->student_number, $group->id, $course->id);

        $this->assertEquals(1, Invitation::count());

        try {
            Invitation::create($student->student_number, $group->id, $course->id);

            $this->assertTrue(false);
        } catch (UserHasAlreadyAnInviteInGroupException $e) {
            $this->assertTrue(true);
        }
    }
}
