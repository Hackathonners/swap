<?php

namespace Tests\Unit;

use Mockery as m;
use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use App\Judite\Contracts\ExchangeLogger;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExchangeTest extends TestCase
{
    use DatabaseTransactions;

    private $loggerMock;

    public function setUp()
    {
        parent::setUp();
        $this->loggerMock = m::mock(ExchangeLogger::class);
        $this->app->instance(ExchangeLogger::class, $this->loggerMock);
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
        $exchange = new Exchange;
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
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $otherToEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
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
        $this->loggerMock->shouldReceive('log')
                         ->once();

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
        $fromShiftId = $fromEnrollment->shift_id;
        $toShiftId = $toEnrollment->shift_id;

        // Execute
        $actualReturn = Exchange::findMatchingExchange($fromEnrollment, $toEnrollment);

        // Assert
        $this->assertNotNull($actualReturn);
        $this->assertEquals(Exchange::class, get_class($actualReturn));
        $this->assertEquals($existingExchange->id, $actualReturn->id);
    }

    /**
     * @expectedException App\Exceptions\CannotExchangeToShiftsOnDifferentCoursesException
     */
    public function testThrowsExceptionWhenExchangedEnrollmentsAreOnDifferentCourses()
    {
        // Prepare
        $fromEnrollment = factory(Enrollment::class)->make();
        $toEnrollment = factory(Enrollment::class)->make();

        // Execute
        $exchange = new Exchange;
        $actualReturn = $exchange->setExchangeEnrollments($fromEnrollment, $toEnrollment);
    }

    /**
     * @expectedException App\Exceptions\CannotExchangeEnrollmentMultipleTimesException
     */
    public function testThrowsExceptionWhenAnEnrollmentIsAlreadyListedForExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $otherToEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $exchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);

        // Execute
        $exchange->setExchangeEnrollments($fromEnrollment, $otherToEnrollment);
    }
}
