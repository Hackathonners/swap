<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExchangeTest extends TestCase
{
    use DatabaseTransactions;

    public function testAddShiftExchangesToEnrollment()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $shifts = factory(Shift::class, 3)->create(['course_id' => $course->id]);
        $enrollment = factory(Enrollment::class)->create([
            'course_id' => $course->id,
            'shift_id' => $shifts->first()->id,
        ]);
        $exchangeShifts = (clone $shifts)->splice(1, 2);

        // Execute
        $actualReturn = $enrollment->setExchanges($exchangeShifts);

        // Assert
        $this->assertEquals(Collection::class, get_class($actualReturn));
        $actualExchanges = Exchange::all();
        $this->assertEquals(2, $actualExchanges->count());
        $this->assertEquals(2, $actualExchanges->where('enrollment_id', $enrollment->id)->count());
        $this->assertEquals(2, $actualExchanges->whereIn('shift_id', $exchangeShifts->pluck('id'))->count());
    }

    /**
     * @expectedException App\Exceptions\CannotExchangeToShiftsOnDifferentCoursesException
     */
    public function testThrowsExceptionWhenExchangedShiftsAreOnDifferentCourses()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $enrollment = factory(Enrollment::class)->create();
        $otherCourseShift = factory(Shift::class)->create();

        // Execute
        $enrollment->setExchanges(collect([$otherCourseShift]));
    }
}
