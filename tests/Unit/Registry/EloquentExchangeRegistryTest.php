<?php

namespace Tests\Unit\Registry;

use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Enrollment;
use App\Judite\Models\ExchangeRegistryEntry;
use App\Judite\Registry\EloquentExchangeRegistry;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentExchangeRegistryTest extends TestCase
{
    use DatabaseTransactions;

    public function testRecordExchange()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->make(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->make(['course_id' => $course->id]);
        $exchangeRegistry = new EloquentExchangeRegistry();

        // Execute
        $exchangeRegistry->record($fromEnrollment, $toEnrollment);

        // Assert
        $actualLogExchange = ExchangeRegistryEntry::first();
        $this->assertEquals(1, ExchangeRegistryEntry::count());
        $this->assertEquals($fromEnrollment->student_id, $actualLogExchange->from_student_id);
        $this->assertEquals($toEnrollment->student_id, $actualLogExchange->to_student_id);
        $this->assertEquals($fromEnrollment->shift_id, $actualLogExchange->from_shift_id);
        $this->assertEquals($toEnrollment->shift_id, $actualLogExchange->to_shift_id);
    }

    public function testPaginateRecords()
    {
        $exchangeRegistry = new EloquentExchangeRegistry();
        $records = factory(ExchangeRegistryEntry::class, 10)->create();

        $actualRecords = $exchangeRegistry->paginate();

        $this->assertTrue($actualRecords instanceof LengthAwarePaginator);
        $this->assertEquals(10, $actualRecords->total());
        collect($actualRecords->items())->each(function ($item) use ($records) {
            $this->assertTrue($records->contains($item));
        });
    }
}
