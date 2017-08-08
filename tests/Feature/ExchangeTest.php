<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExchangeTest extends TestCase
{
    use DatabaseTransactions;

    public function testStudentCanRequestShiftEnrollmentsExchange()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create([
            'student_id' => $student->id,
            'course_id' => $course->id,
        ]);
        $toEnrollment = factory(Enrollment::class)->create([
            'course_id' => $course->id,
        ]);
        $requestData = [
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ];

        // Execute
        $response = $this->actingAs($student->user)
                         ->post(route('exchanges.create'), $requestData);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(1, Exchange::count());
        $actualExchange = Exchange::first();
        $this->assertEquals($fromEnrollment->id, $actualExchange->from_enrollment_id);
        $this->assertEquals($toEnrollment->id, $actualExchange->to_enrollment_id);
    }

    public function testEnrollmentCannotBeExchangeByOtherStudentThanTheOwnerStudent()
    {
        // Prepare
        $unauthorizedStudent = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $toEnrollment = factory(Enrollment::class)->create(['course_id' => $course->id]);
        $requestData = [
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ];

        // Execute
        $response = $this->actingAs($unauthorizedStudent->user)
                         ->post(route('exchanges.create'), $requestData);

        // Assert
        $response->assertStatus(403);
        $this->assertEquals(0, Exchange::count());
    }

    public function testStudentCanConfirmExchange()
    {
        // Prepare
        $student = factory(Student::class)->create();
        $course = factory(Course::class)->create();
        $fromEnrollment = factory(Enrollment::class)->create();
        $toEnrollment = factory(Enrollment::class)->create(['student_id' => $student->id]);
        $exchange = factory(Exchange::class)->create([
            'from_enrollment_id' => $fromEnrollment->id,
            'to_enrollment_id' => $toEnrollment->id,
        ]);
        $requestData = ['exchange_id' => $exchange->id];

        // Execute
        $response = $this->actingAs($student->user)
                         ->post(route('exchanges.confirm'), $requestData);

        // Assert
        $response->assertRedirect(route('home'));
        $actualFromEnrollment = Enrollment::find($fromEnrollment->id);
        $actualToEnrollment = Enrollment::find($toEnrollment->id);
        $this->assertEquals($fromEnrollment->shift->id, $actualToEnrollment->shift->id);
        $this->assertEquals($toEnrollment->shift->id, $actualFromEnrollment->shift->id);
    }

    public function testExchangeCannotBeConfirmedByOtherStudentThanTheTargetStudent()
    {
        // Prepare
        $unauthorizedStudent = factory(Student::class)->create();
        $exchange = factory(Exchange::class)->create();

        // Execute
        $response = $this->actingAs($unauthorizedStudent->user)
                         ->post(route('exchanges.confirm', $exchange->id));

        // Assert
        $response->assertStatus(403);
        $this->assertEquals(1, Exchange::count());
    }
}
