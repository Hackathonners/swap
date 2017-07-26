<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Course;

class CoursePresenterTest extends TestCase
{
    public function testGetOrdinalYear()
    {
        // Prepare
        $courseFirstYear = factory(Course::class)->make(['year' => 1]);

        // Execute
        $actualReturn = $courseFirstYear->present()->getOrdinalYear();

        // Assert
        $this->assertEquals('First', $actualReturn);
    }
}
