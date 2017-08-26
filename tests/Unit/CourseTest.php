<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CourseTest extends TestCase
{
    use DatabaseTransactions;

    public function testAddShift()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $shift = new Shift();
        $shift->tag = 'test';

        // Execute
        $actualReturn = $course->addShift($shift);

        // Assert
        $this->assertSame($course, $actualReturn);
        $this->assertEquals(1, Shift::count());
        $actualShift = $course->shifts()->first();
        $this->assertEquals($course->id, $actualShift->course_id);
    }

    public function testGetShiftByTag()
    {
        // Prepare
        $course = factory(Course::class)->create();
        $shift = factory(Shift::class)->create([
            'tag' => 'test',
            'course_id' => $course->id,
        ]);

        // Execute
        $actualReturn = $course->getShiftByTag('test');

        // Assert
        $this->assertEquals(Shift::class, get_class($actualReturn));
        $this->assertEquals($shift->id, $actualReturn->id);
    }

    public function testGetOrderedListOfCourses()
    {
        // Prepare
        factory(Course::class)->create([
            'name' => 'C32',
            'year' => 3,
            'semester' => 2,
        ]);
        factory(Course::class)->create([
            'name' => 'B32',
            'year' => 3,
            'semester' => 2,
        ]);
        factory(Course::class)->create([
            'name' => 'A21',
            'year' => 2,
            'semester' => 1,
        ]);

        // Execute
        $actualReturn = Course::orderedList()->get();

        // Assert
        $expectedOrderedCourses = Course::orderBy('year', 'asc')
            ->orderBy('semester', 'asc')
            ->orderBy('name', 'asc')
            ->get();
        $this->assertEquals($expectedOrderedCourses, $actualReturn);
    }
}
