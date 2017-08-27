<?php

namespace Tests\Feature;

use Carbon\Carbon;
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

        // Enable exchanges period
        $settings = app('settings');
        $settings->exchanges_start_at = Carbon::yesterday();
        $settings->exchanges_end_at = Carbon::tomorrow();
        $settings->save();
    }

    /** @test */
    public function a_student_can_delete_a_shift_exchange_request()
    {
        // Execute
        $this->actingAs($this->fromEnrollment->student->user);
        $response = $this->delete(route('exchanges.destroy', $this->exchange->id));

        // Assert
        $response->assertRedirect();
        $this->assertEquals(0, Exchange::count());
    }

    /** @test */
    public function a_student_may_not_delete_a_shift_exchange_request_of_another_student()
    {
        // Prepare
        $unauthorizedStudent = factory(Student::class)->create();

        // Execute
        $this->actingAs($unauthorizedStudent->user);
        $response = $this->delete(route('exchanges.destroy', $this->exchange->id));

        // Assert
        $response->assertStatus(404);
        $this->assertEquals(1, Exchange::count());
        $this->assertEnrollmentsRemainUnchanged();
    }

    /** @test */
    public function unauthenticated_users_may_not_delete_exchanges()
    {
        // Execute
        $response = $this->delete(route('exchanges.destroy', $this->exchange->id));

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
