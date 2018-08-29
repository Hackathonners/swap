<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvitationTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentHasOnlyOneInvitationToEachGroup()
    {
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $group = factory(Group::class)->create();

        $validInvitation = factory(Invitation::class)->create([
            'student_number' => $student->student_number,
            'course_id' => $course->id,
            'group_id' => $group->id,
        ]);

        try {
            $invalidInvitation = factory(Invitation::class)->create([
                'student_number' => $student->student_number,
                'course_id' => $course->id,
                'group_id' => $group->id,
            ]);

            $this->assertTrue(false);
        } catch (QueryException $e) {
            $this->assertTrue(true);
        }
    }
}
