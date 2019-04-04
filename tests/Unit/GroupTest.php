<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Exceptions\GroupIsFullException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Exceptions\StudentIsNotEnrolledInCourseException;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    private $course;

    public function setUp()
    {
        parent::setUp();
        // We need this enable here to set min/max group numbers
        $this->enableGroupsPeriod();
        $this->course = factory(Course::class)->create();
    }

    public function testGroupIsEligibleForAcceptance()
    {
        // Prepare
        $eligibleGroup = factory(Group::class)->create();
        $ineligibleGroup = factory(Group::class)->create();

        // Execute
        $eligibleGroup->students()->saveMany(factory(Student::class, 2)->make());
        $ineligibleGroup->students()->saveMany(factory(Student::class, 1)->make());

        // Assert
        $this->assertTrue($eligibleGroup->isEligibleForAcceptance());
        $this->assertFalse($ineligibleGroup->isEligibleForAcceptance());
    }

    public function testGroupIsFull()
    {
        // Prepare
        $fullGroup = factory(Group::class)->create();
        $notFullGroup = factory(Group::class)->create();

        // Execute
        $fullGroup->students()->saveMany(factory(Student::class, 4)->make());
        $notFullGroup->students()->saveMany(factory(Student::class, 3)->make());

        // Assert
        $this->assertTrue($fullGroup->isFull());
        $this->assertFalse($notFullGroup->isFull());
    }

    public function testGroupIsEmpty()
    {
        $emptyGroup = factory(Group::class)->create();
        $notEmptyGroup = factory(Group::class)->create();

        $notEmptyGroup->students()->saveMany(factory(Student::class, 1)->make());

        $this->assertTrue($emptyGroup->isEmpty());
        $this->assertFalse($notEmptyGroup->isEmpty());
    }

    public function testGroupIsAvailableToJoin()
    {
        $availableGroup = factory(Group::class)->create();
        $notAvailableGroup = factory(Group::class)->create();

        $availableGroup->students()->saveMany(factory(Student::class, 2)->make());
        $notAvailableGroup->students()->saveMany(factory(Student::class, 4)->make());

        $this->assertTrue($availableGroup->isAvailableToJoin());
        $this->assertFalse($notAvailableGroup->isAvailableToJoin());
    }

    public function testAddMember()
    {
        $group = $this->course->groups()->save(factory(Group::class)->make());
        $student = factory(Student::class)->create();

        $student->enroll($this->course);
        $group->addMember($student);

        $this->assertFalse($group->isEmpty());
        $this->assertTrue($student->isInvitedToGroup($group));
    }

    public function testTryToAddMemberNotEnrolledInCourse()
    {
        $group = $this->course->groups()->save(factory(Group::class)->make());
        $student = factory(Student::class)->create();

        try {
            $group->addMember($student);
            $this->fail('Student exception was not threw.');
        } catch (StudentIsNotEnrolledInCourseException $e) {
            $this->assertTrue($group->isEmpty());
            $this->assertFalse($student->isMemberOfGroup($group));
        }
    }

    public function testTryToAddExistingMember()
    {
        // Prepare
        $group = $this->course->groups()->save(factory(Group::class)->make());
        $student = factory(Student::class)->create();
        $student->enroll($this->course);

        // Execute
        $group->students()->save($student);
        $numOfStudentsBefore = $group->students()->count();
        $group->addMember($student);
        $numOfStudentsAfter = $group->students()->count();

        // Assert
        $this->assertEquals(1, $numOfStudentsBefore);
        $this->assertEquals(1, $numOfStudentsAfter);
    }

    public function testRemoveMember()
    {
        // Prepare
        $group = factory(Group::class)->create();
        $group->students()->saveMany(factory(Student::class, 2)->make());
        $student = factory(Student::class)->create();

        // Execute
        $group->students()->save($student);
        $studentWasInGroup = $student->isAssociatedToGroup($group);
        $group->removeMember($student);
        
        // Assert
        $this->assertTrue($studentWasInGroup);
        $this->assertFalse($student->isMemberOfGroup($group));
        $this->assertEquals(2, $group->students()->count());
    }

    public function testGroupIsDeletedWhenLastElementIsRemoved()
    {
        $group = factory(Group::class)->create();
        $student = factory(Student::class)->create();

        $group->students()->save($student);
        $group->removeMember($student);
        
        $this->assertEquals(0, Group::count());
    }

    public function testThrowExceptionWhenGroupIsFull()
    {
        $group = factory(Group::class)->create();
        $group->students()->saveMany(factory(Student::class, 4)->make());
        $student = factory(Student::class)->create();

        try {
            $group->addMember($student);
            $this->fail('Group exception was not threw.');
        } catch (GroupIsFullException $e) {
            $this->assertEquals(4, $group->students()->count());
        }
    }

    public function testNewMemberIsPendingInGroup()
    {
        $group = $this->course->groups()->save(factory(Group::class)->make());
        $student = factory(Student::class)->create();
        $student->enroll($this->course);

        $group->addMember($student);

        $this->assertEquals(0, $group->confirmedStudents()->count());
        $this->assertEquals(1, $group->pendingStudents()->count());
    }
}
