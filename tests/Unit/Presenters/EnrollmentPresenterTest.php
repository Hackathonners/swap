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
