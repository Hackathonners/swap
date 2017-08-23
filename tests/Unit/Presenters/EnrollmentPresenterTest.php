<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Shift;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EnrollmentPresenterTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetShiftTag()
    {
        // Prepare
        $enrollment = factory(Enrollment::class)->make([
            'shift_id' => factory(Shift::class)->create(['tag' => 'test'])->id,
        ]);

        // Execute
        $actualReturn = $enrollment->present()->getShiftTag();

        // Assert
        $this->assertEquals('test', $actualReturn);
    }

    public function testGetUpdatedAt()
    {
        // Prepare
        $enrollment = factory(Enrollment::class)->create();

        // Execute
        $actualReturn = $enrollment->present()->getUpdatedAt();

        // Assert
        $this->assertEquals($enrollment->updated_at->toDayDateTimeString(), $actualReturn);
    }

    public function testInlineToString()
    {
        // Prepare
        $enrollment = factory(Enrollment::class)->create();

        // Execute
        $actualReturn = $enrollment->present()->inlineToString();

        // Assert
        $expected = $enrollment->student->user->name
            .' ('.$enrollment->student->student_number.')'
            .' - '.$enrollment->shift->tag
            .' on '.$enrollment->course->name;

        $this->assertEquals($expected, $actualReturn);
    }

    public function testGetShiftTagPlaceholderWhenShiftIsUndefined()
    {
        // Prepare
        $enrollment = factory(Enrollment::class)->make(['shift_id' => null]);

        // Execute
        $actualReturn = $enrollment->present()->getShiftTag('no-shift');

        // Assert
        $this->assertEquals('no-shift', $actualReturn);
    }
}
