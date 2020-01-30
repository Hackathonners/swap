<?php

namespace Tests\Feature;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->enableExchangesPeriod();
        $this->fromEnrollment = factory(Enrollment::class)->create();
        $this->toEnrollment = factory(Enrollment::class)->create([
            'course_id' => $this->fromEnrollment->course->id,
        ]);
    }

    /** @test */
    public function a_student_can_propose_a_shift_exchange()
    {
        $requestData = ['to_enrollment_id' => $this->toEnrollment->id];

        $response = $this->actingAs($this->fromEnrollment->student->user)
            ->post(route('exchanges.store', $this->fromEnrollment->id), $requestData);

        $response->assertRedirect();
        $this->assertEquals(1, Exchange::count());
        $actualExchange = Exchange::first();
        $this->assertEquals($this->fromEnrollment->id, $actualExchange->from_enrollment_id);
        $this->assertEquals($this->toEnrollment->id, $actualExchange->to_enrollment_id);
    }

    /** @test */
    public function a_student_may_not_propose_a_shift_exchange_of_a_third_party_enrollment()
    {
        $unauthorizedStudent = factory(Student::class)->create();
        $requestData = ['to_enrollment_id' => $this->toEnrollment->id];

        $response = $this->actingAs($unauthorizedStudent->user)
            ->post(route('exchanges.store', $this->fromEnrollment->id), $requestData);

        $response->assertStatus(404);
        $this->assertEquals(0, Exchange::count());
    }

    /** @test */
    public function unauthenticated_users_may_not_create_exchanges()
    {
        $requestData = ['to_enrollment_id' => $this->toEnrollment->id];

        $response = $this->post(route('exchanges.store', $this->fromEnrollment->id), $requestData);

        $response->assertRedirect(route('login'));
        $this->assertEquals(0, Exchange::count());
    }
}
