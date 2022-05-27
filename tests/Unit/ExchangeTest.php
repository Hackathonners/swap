<?php

namespace Tests\Unit;

use App\Judite\Models\Student;
use Mockery as m;
use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use App\Judite\Models\Solver;
use App\Judite\Models\Shift;
use App\Judite\Contracts\Registry\ExchangeRegistry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\EnrollmentCannotBeExchangedException;
use App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException;

class ExchangeTest extends TestCase
{
    use DatabaseTransactions;

    private $registryMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->registryMock = m::mock(ExchangeRegistry::class);
        $this->app->instance(ExchangeRegistry::class, $this->registryMock);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    public function testSetEnrollmentsToExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->make(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->make(['course_id' => $course->id]);

        // Execute
        $exchange = new Exchange();
        $actualReturn = $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);

        // Assert
        $this->assertSame($actualReturn, $exchange);
        $this->assertEquals($fromEnrollment->student_id, $exchange->fromEnrollment->student_id);
        $this->assertEquals($fromEnrollment->shift_id, $exchange->fromEnrollment->shift_id);
        $this->assertEquals($toEnrollment->student_id, $exchange->toEnrollment->student_id);
        $this->assertEquals($toEnrollment->shift_id, $exchange->toEnrollment->shift_id);
    }

    public function testPerformExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $otherFromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $exchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        factory(Exchange::class)->create([
            'from_enrollment_id' => $otherFromEnrollment->id,
            'to_enrollment_id' => $fromEnrollment->id,
        ]);
        $fromShiftId = $fromEnrollment->shift_id;
        $toShiftId = $toEnrollment->shift_id;
        $this->registryMock->shouldReceive('record')->once();

        // Execute
        $actualReturn = $exchange->perform();

        // Assert
        $this->assertSame($exchange, $actualReturn);
        $this->assertEquals(0, Exchange::count());
        $actualFromEnrollment = Enrollment::find($fromEnrollment->id);
        $actualToEnrollment = Enrollment::find($toEnrollment->id);
        $this->assertEquals($toShiftId, $actualFromEnrollment->shift_id);
        $this->assertEquals($fromShiftId, $actualToEnrollment->shift_id);
    }

    public function testPerformAutoExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $student = factory(Student::class)->create();
        $otherStudent = factory(Student::class)->create();
        $shift = factory(Shift::class)->create(['course_id' => $course->id]);
        $otherShift = factory(Shift::class)->create(['course_id' => $course->id]);

        $fromEnrollment = factory(Enrollment::class)->create(['student_id'=>$student->id,'course_id' => $course->id,'shift_id' => $shift->id]);
        $toEnrollment = factory(Enrollment::class)->create(['student_id'=>null,'course_id' => $course->id,'shift_id' => $otherShift->id]);

        $otherFromEnrollment = factory(Enrollment::class)->create(['student_id'=>$otherStudent->id,'course_id' => $course->id,'shift_id' => $otherShift->id]);
        $otherToEnrollment = factory(Enrollment::class)->create(['student_id'=>null,'course_id' => $course->id,'shift_id' => $shift->id]);

        $exchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        $otherExchange =factory(Exchange::class)->create([
            'from_enrollment_id' => $otherFromEnrollment->id,
            'to_enrollment_id' => $otherToEnrollment->id,
        ]);

        $this->registryMock->shouldReceive('record')->twice();

        // Execute
        $actualReturn = Solver::SolveAutomicExchangesOfCourse($course);
        // Assert
        $this->assertNotEquals($shift->tag,$otherShift->tag);
        $this->assertContains($exchange->id,$actualReturn);
        $this->assertContains($otherExchange->id,$actualReturn);
        $this->assertEquals(0, Exchange::count());
        $actualFromEnrollment = Enrollment::find($fromEnrollment->id);
        $actualOtherFromEnrollment = Enrollment::find($otherFromEnrollment->id);
        $this->assertEquals($shift->id, $actualOtherFromEnrollment->shift_id);
        $this->assertEquals($otherShift->id, $actualFromEnrollment->shift_id);
    }


    public function testFindMatchingExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $existingExchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $toEnrollment->id,
            'to_enrollment_id' => $fromEnrollment->id,
        ]);

        // Execute
        $actualReturn = Exchange::findMatchingExchange($fromEnrollment, $toEnrollment);

        // Assert
        $this->assertNotNull($actualReturn);
        $this->assertEquals(Exchange::class, get_class($actualReturn));
        $this->assertEquals($existingExchange->id, $actualReturn->id);
    }

    public function testFilterOwnedExchanges()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $ownedExchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        factory(Exchange::class)->create();

        // Execute
        $actualReturn = Exchange::ownedBy($fromEnrollment->student)->get();

        // Assert
        $this->assertEquals(1, $actualReturn->count());
        $this->assertTrue($actualReturn->contains($ownedExchange));
    }

    public function testFilterFromEnrollment()
    {
        // Prepare
        $fromEnrollment = factory(Enrollment::class)->create();
        $otherFromEnrollment = factory(Enrollment::class)->create();
        $fromEnrollmentExchanges = factory(Exchange::class, 2)->create([
            'from_enrollment_id' => $fromEnrollment->id,
        ]);
        $otherFromEnrollmentExchanges = factory(Exchange::class, 2)->create([
            'from_enrollment_id' => $otherFromEnrollment->id,
        ]);
        factory(Exchange::class)->create(['to_enrollment_id' => $fromEnrollment->id]);
        factory(Exchange::class)->create(['to_enrollment_id' => $otherFromEnrollment->id]);

        // Execute
        $enrollments = collect([$fromEnrollment, $otherFromEnrollment]);
        $actualReturn = Exchange::whereFromEnrollmentIn($enrollments->pluck('id'))->get();

        // Assert
        $expectedExchanges = $fromEnrollmentExchanges->merge($otherFromEnrollmentExchanges);
        $this->assertEquals($expectedExchanges->pluck('id'), $actualReturn->pluck('id'));
    }

    public function testFilterToEnrollment()
    {
        // Prepare
        $toEnrollment = factory(Enrollment::class)->create();
        $otherToEnrollment = factory(Enrollment::class)->create();
        $toEnrollmentExchanges = factory(Exchange::class, 2)->create([
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        $otherToEnrollmentExchanges = factory(Exchange::class, 2)->create([
            'to_enrollment_id' => $otherToEnrollment->id,
        ]);
        factory(Exchange::class)->create(['from_enrollment_id' => $toEnrollment->id]);
        factory(Exchange::class)->create(['from_enrollment_id' => $otherToEnrollment->id]);

        // Execute
        $enrollments = collect([$toEnrollment, $otherToEnrollment]);
        $actualReturn = Exchange::whereToEnrollmentIn($enrollments->pluck('id'))->get();

        // Assert
        $expectedExchanges = $toEnrollmentExchanges->merge($otherToEnrollmentExchanges);
        $this->assertEquals($expectedExchanges->pluck('id'), $actualReturn->pluck('id'));
    }

    public function testGetCourse()
    {
        // Prepare
        $exchange = factory(Exchange::class)->create();

        // Assert
        $fromEnrollment = $exchange->fromEnrollment()->first();
        $this->assertEquals($fromEnrollment->course->id, $exchange->course()->id);
    }

    public function testGetFromShift()
    {
        // Prepare
        $exchange = factory(Exchange::class)->create();

        // Assert
        $fromEnrollment = $exchange->fromEnrollment()->first();
        $this->assertEquals($fromEnrollment->shift->id, $exchange->fromShift()->id);
    }

    public function testGetToShift()
    {
        // Prepare
        $exchange = factory(Exchange::class)->create();

        // Assert
        $toEnrollment = $exchange->toEnrollment()->first();
        $this->assertEquals($toEnrollment->shift->id, $exchange->toShift()->id);
    }

    public function testGetFromStudent()
    {
        // Prepare
        $exchange = factory(Exchange::class)->create();

        // Assert
        $fromEnrollment = $exchange->fromEnrollment()->first();
        $this->assertEquals($fromEnrollment->student->id, $exchange->fromStudent()->id);
    }

    public function testGetToStudent()
    {
        // Prepare
        $exchange = factory(Exchange::class)->create();

        // Assert
        $toEnrollment = $exchange->toEnrollment()->first();
        $this->assertEquals($toEnrollment->student->id, $exchange->toStudent()->id);
    }

    public function testThrowsExceptionWhenExchangedEnrollmentsAreOnDifferentCourses()
    {
        $this->expectException(ExchangeEnrollmentsOnDifferentCoursesException::class);

        // Prepare
        $fromEnrollment = factory(Enrollment::class)->make();
        $toEnrollment = factory(Enrollment::class)->make();

        // Execute
        $exchange = new Exchange();
        $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
    }

    public function testThrowsExceptionWhenAnEnrollmentIsAlreadyListedForExchange()
    {
        $this->expectException(EnrollmentCannotBeExchangedException::class);

        // Prepare
        $existingExchange = factory(Exchange::class)->create();
        $toEnrollment = factory(Enrollment::class)->create([
            'course_id' => $existingExchange->course()->id,
        ]);

        // Execute
        $exchange = Exchange::make();
        $exchange->setExchangeEnrollments($existingExchange->fromEnrollment, $toEnrollment);
    }

    public function testThrowsExceptionWhenAnEnrollmentWithoutAssociatedShiftIsExchanged()
    {
        $this->expectException(EnrollmentCannotBeExchangedException::class);

        // Prepare
        $fromEnrollment = factory(Enrollment::class)->create();
        $toEnrollment = factory(Enrollment::class)->create([
            'course_id' => $fromEnrollment->course->id,
            'shift_id' => null,
        ]);

        // Execute
        $exchange = Exchange::make();
        $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
    }
}
