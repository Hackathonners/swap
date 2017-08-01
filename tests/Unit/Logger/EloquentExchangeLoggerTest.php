<?php

namespace Tests\Unit\Logger;

use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Enrollment;
use App\Judite\Models\LogExchange;
use App\Judite\Logger\EloquentExchangeLogger;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EloquentExchangeLoggerTest extends TestCase
{
    use DatabaseTransactions;

    public function testLogExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->make(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->make(['course_id' => $course->id]);
        $exchangeLogger = new EloquentExchangeLogger;

        // Execute
        $exchangeLogger->log($fromEnrollment, $toEnrollment);

        // Assert
        $actualLogExchange = LogExchange::first();
        $this->assertEquals(1, LogExchange::count());
        $this->assertEquals($fromEnrollment->student_id, $actualLogExchange->from_student_id);
        $this->assertEquals($toEnrollment->student_id, $actualLogExchange->to_student_id);
        $this->assertEquals($fromEnrollment->shift_id, $actualLogExchange->from_shift_id);
        $this->assertEquals($toEnrollment->shift_id, $actualLogExchange->to_shift_id);
    }
}
