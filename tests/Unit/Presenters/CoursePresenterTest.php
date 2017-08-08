<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Course;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CoursePresenterTest extends TestCase
{
    use DatabaseTransactions;

    public function testGetOrdinalYear()
    {
        // Prepare
        $courseFirstYear = factory(Course::class)->make(['year' => 1]);

        // Execute
        $actualReturn = $courseFirstYear->present()->getOrdinalYear();

        // Assert
        $this->assertEquals('First', $actualReturn);
    }

    public function testGetOrdinalSemester()
    {
        // Prepare
        $courseFirstSemester = factory(Course::class)->make(['semester' => 1]);

        // Execute
        $actualReturn = $courseFirstSemester->present()->getOrdinalSemester();

        // Assert
        $this->assertEquals('1st', $actualReturn);
    }
}
