<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExchangeTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentCanRequestShiftsExchange()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $shifts = factory(Shift::class, 4)->create(['course_id' => $course->id]);
        $enrollment = factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'shift_id' => $shifts->first()->id,
        ]);
        $exchangeShifts = (clone $shifts)->splice(1, 2);
        $requestData = [
            'enrollment_id' => $enrollment->id,
            'shifts' => $exchangeShifts->pluck('id'),
        ];

        // Execute
        $response = $this->actingAs($student->user)
                         ->post(route('exchanges.create'), $requestData);

        // Assert
        $response->assertStatus(200);
        $actualExchanges = $enrollment->exchanges()->get();
        $this->assertEquals(2, $actualExchanges->count());
        $this->assertEquals(2, $actualExchanges->where('enrollment_id', $enrollment->id)->count());
        $this->assertEquals(2, $actualExchanges->whereIn('shift_id', $exchangeShifts->pluck('id'))->count());
    }
}
