<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteExchangeTest extends TestCase
{
    use DatabaseTransactions;

    protected $exchange;
    protected $fromEnrollment;
    protected $toEnrollment;

    public function setUp()
    {
        parent::setUp();
        $this->exchange = factory(Exchange::class)->create();
        $this->fromEnrollment = $this->exchange->fromEnrollment;
        $this->toEnrollment = $this->exchange->toEnrollment;
    }

    /** @test */
    public function a_student_can_delete_a_shift_exchange_request()
    {
        // Prepare
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $this->actingAs($this->fromEnrollment->student->user);
        $response = $this->delete(route('exchanges.destroy'), $requestData);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals(0, Exchange::count());
    }

    /** @test */
    public function a_student_may_not_delete_a_shift_exchange_request_of_another_student()
    {
        // Prepare
        $unauthorizedStudent = factory(Student::class)->create();
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $this->actingAs($unauthorizedStudent->user);
        $response = $this->delete(route('exchanges.destroy'), $requestData);

        // Assert
        $response->assertStatus(403);
        $this->assertEquals(1, Exchange::count());
        $this->assertEnrollmentsRemainUnchanged();
    }

    /** @test */
    public function unauthenticated_users_may_not_delete_exchanges()
    {
        // Prepare
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $response = $this->delete(route('exchanges.destroy', $requestData));

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertEquals(1, Exchange::count());
        $this->assertEnrollmentsRemainUnchanged();
    }

    protected function assertEnrollmentsRemainUnchanged()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualFromEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualToEnrollment->shift->id);
    }
}
