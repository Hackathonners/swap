<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateExchangeTest extends TestCase
{
    use DatabaseTransactions;

    protected $fromEnrollment;
    protected $toEnrollment;

    public function setUp()
    {
        parent::setUp();
        $this->fromEnrollment = factory(Enrollment::class)->create();
        $this->toEnrollment = factory(Enrollment::class)->create([
            'course_id' => $this->fromEnrollment->course->id,
        ]);

        // Enable exchanges period
        $settings = app('settings');
        $settings->exchanges_start_at = Carbon::yesterday();
        $settings->exchanges_end_at = Carbon::tomorrow();
        $settings->save();
    }

    /** @test */
    public function a_student_can_propose_a_shift_exchange()
    {
        // Prepare
        $requestData = [
            'from_enrollment_id' => $this->fromEnrollment->id,
            'to_enrollment_id' => $this->toEnrollment->id,
        ];

        // Execute
        $this->actingAs($this->fromEnrollment->student->user);
        $response = $this->post(route('exchanges.store'), $requestData);

        // Assert
        $response->assertRedirect();
        $this->assertEquals(1, Exchange::count());
        $actualExchange = Exchange::first();
        $this->assertEquals($this->fromEnrollment->id, $actualExchange->from_enrollment_id);
        $this->assertEquals($this->toEnrollment->id, $actualExchange->to_enrollment_id);
    }

    /** @test */
    public function a_student_may_not_propose_a_shift_exchange_of_a_third_party_enrollment()
    {
        // Prepare
        $unauthorizedStudent = factory(Student::class)->create();
        $requestData = [
            'from_enrollment_id' => $this->fromEnrollment->id,
            'to_enrollment_id' => $this->toEnrollment->id,
        ];

        // Execute
        $this->actingAs($unauthorizedStudent->user);
        $response = $this->post(route('exchanges.store'), $requestData);

        // Assert
        $response->assertStatus(302); // TODO: effetive unauthorized exception handling
        $this->assertEquals(0, Exchange::count());
    }

    /** @test */
    public function unauthenticated_users_may_not_create_exchanges()
    {
        // Prepare
        $requestData = [
            'from_enrollment_id' => $this->fromEnrollment->id,
            'to_enrollment_id' => $this->toEnrollment->id,
        ];

        // Execute
        $response = $this->post(route('exchanges.store', $requestData));

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertEquals(0, Exchange::count());
    }
}
