<?php

namespace Tests\Unit;

use Mockery as m;
use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\DirectExchange;
use App\Judite\Models\Enrollment;
use App\Judite\Contracts\Registry\ExchangeRegistry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Exceptions\EnrollmentCannotBeExchangedException;
use App\Exceptions\ExchangeEnrollmentsOnDifferentCoursesException;

class ExchangeTest extends TestCase
{
    use DatabaseTransactions;

    private $registryMock;

    public function setUp()
    {
        parent::setUp();
        $this->registryMock = m::mock(ExchangeRegistry::class);
        $this->app->instance(ExchangeRegistry::class, $this->registryMock);
    }

    public function tearDown()
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
        $exchange = new DirectExchange();
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
        $exchange = factory(DirectExchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        factory(DirectExchange::class)->create([
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
        $this->assertEquals(0, DirectExchange::count());
        $actualFromEnrollment = Enrollment::find($fromEnrollment->id);
        $actualToEnrollment = Enrollment::find($toEnrollment->id);
        $this->assertEquals($toShiftId, $actualFromEnrollment->shift_id);
        $this->assertEquals($fromShiftId, $actualToEnrollment->shift_id);
    }

    public function testFindMatchingExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $existingExchange = factory(DirectExchange::class)->create([
            'from_enrollment_id' => $toEnrollment->id,
            'to_enrollment_id' => $fromEnrollment->id,
        ]);

        // Execute
        $actualReturn = DirectExchange::findMatchingExchange($fromEnrollment, $toEnrollment);

        // Assert
        $this->assertNotNull($actualReturn);
        $this->assertEquals(DirectExchange::class, get_class($actualReturn));
        $this->assertEquals($existingExchange->id, $actualReturn->id);
    }

    public function testFilterOwnedExchanges()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $ownedExchange = factory(DirectExchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        factory(DirectExchange::class)->create();

        // Execute
        $actualReturn = DirectExchange::ownedBy($fromEnrollment->student)->get();

        // Assert
        $this->assertEquals(1, $actualReturn->count());
        $this->assertTrue($actualReturn->contains($ownedExchange));
    }

    public function testFilterFromEnrollment()
    {
        // Prepare
        $fromEnrollment = factory(Enrollment::class)->create();
        $otherFromEnrollment = factory(Enrollment::class)->create();
        $fromEnrollmentExchanges = factory(DirectExchange::class, 2)->create([
            'from_enrollment_id' => $fromEnrollment->id,
        ]);
        $otherFromEnrollmentExchanges = factory(DirectExchange::class, 2)->create([
            'from_enrollment_id' => $otherFromEnrollment->id,
        ]);
        factory(DirectExchange::class)->create(['to_enrollment_id' => $fromEnrollment->id]);
        factory(DirectExchange::class)->create(['to_enrollment_id' => $otherFromEnrollment->id]);

        // Execute
        $enrollments = collect([$fromEnrollment, $otherFromEnrollment]);
        $actualReturn = DirectExchange::whereFromEnrollmentIn($enrollments->pluck('id'))->get();

        // Assert
        $expectedExchanges = $fromEnrollmentExchanges->merge($otherFromEnrollmentExchanges);
        $this->assertEquals($expectedExchanges->pluck('id'), $actualReturn->pluck('id'));
    }

    public function testFilterToEnrollment()
    {
        // Prepare
        $toEnrollment = factory(Enrollment::class)->create();
        $otherToEnrollment = factory(Enrollment::class)->create();
        $toEnrollmentExchanges = factory(DirectExchange::class, 2)->create([
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        $otherToEnrollmentExchanges = factory(DirectExchange::class, 2)->create([
            'to_enrollment_id' => $otherToEnrollment->id,
        ]);
        factory(DirectExchange::class)->create(['from_enrollment_id' => $toEnrollment->id]);
        factory(DirectExchange::class)->create(['from_enrollment_id' => $otherToEnrollment->id]);

        // Execute
        $enrollments = collect([$toEnrollment, $otherToEnrollment]);
        $actualReturn = DirectExchange::whereToEnrollmentIn($enrollments->pluck('id'))->get();

        // Assert
        $expectedExchanges = $toEnrollmentExchanges->merge($otherToEnrollmentExchanges);
        $this->assertEquals($expectedExchanges->pluck('id'), $actualReturn->pluck('id'));
    }

    public function testGetCourse()
    {
        // Prepare
        $exchange = factory(DirectExchange::class)->create();

        // Assert
        $fromEnrollment = $exchange->fromEnrollment()->first();
        $this->assertEquals($fromEnrollment->course->id, $exchange->course()->id);
    }

    public function testGetFromShift()
    {
        // Prepare
        $exchange = factory(DirectExchange::class)->create();

        // Assert
        $fromEnrollment = $exchange->fromEnrollment()->first();
        $this->assertEquals($fromEnrollment->shift->id, $exchange->fromShift()->id);
    }

    public function testGetToShift()
    {
        // Prepare
        $exchange = factory(DirectExchange::class)->create();

        // Assert
        $toEnrollment = $exchange->toEnrollment()->first();
        $this->assertEquals($toEnrollment->shift->id, $exchange->toShift()->id);
    }

    public function testGetFromStudent()
    {
        // Prepare
        $exchange = factory(DirectExchange::class)->create();

        // Assert
        $fromEnrollment = $exchange->fromEnrollment()->first();
        $this->assertEquals($fromEnrollment->student->id, $exchange->fromStudent()->id);
    }

    public function testGetToStudent()
    {
        // Prepare
        $exchange = factory(DirectExchange::class)->create();

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
        $exchange = new DirectExchange();
        $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
    }

    public function testThrowsExceptionWhenAnEnrollmentIsAlreadyListedForExchange()
    {
        $this->expectException(EnrollmentCannotBeExchangedException::class);

        // Prepare
        $existingExchange = factory(DirectExchange::class)->create();
        $toEnrollment = factory(Enrollment::class)->create([
            'course_id' => $existingExchange->course()->id,
        ]);

        // Execute
        $exchange = DirectExchange::make();
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
        $exchange = DirectExchange::make();
        $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
    }
}
