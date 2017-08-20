<?php

namespace Tests\Feature;

use Mail;
use Carbon\Carbon;
use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfirmExchangeTest extends TestCase
{
    use DatabaseTransactions;

    protected $exchange;
    protected $fromEnrollment;
    protected $toEnrollment;

    public function setUp()
    {
        parent::setUp();
        Mail::fake();
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
    public function a_student_can_confirm_a_proposed_exchange()
    {
        // Prepare
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $this->actingAs($this->toEnrollment->student->user);
        $response = $this->post(route('exchanges.confirm', $requestData));

        // Assert
        $response->assertRedirect(route('home'));
        $this->assertEnrollmentsAreUpdated();
    }

    /** @test */
    public function a_student_may_not_confirm_its_own_proposed_exchange()
    {
        // Prepare
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $this->actingAs($this->fromEnrollment->student->user);
        $response = $this->post(route('exchanges.confirm', $requestData));

        // Assert
        $response->assertStatus(302); // TODO: effetive unauthorized exception handling
        $this->assertEnrollmentsRemainUnchanged();
    }

    /** @test */
    public function a_student_may_not_confirm_a_third_party_exchange()
    {
        // Prepare
        $unauthorizedStudent = factory(Student::class)->create();
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $this->actingAs($unauthorizedStudent->user);
        $response = $this->post(route('exchanges.confirm', $requestData));

        // Assert
        $response->assertStatus(302); // TODO: effetive unauthorized exception handling
        $this->assertEnrollmentsRemainUnchanged();
    }

    /** @test */
    public function admins_may_not_confirm_exchanges()
    {
        // Prepare
        $admin = factory(User::class)->states('admin')->create();
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $this->actingAs($admin);
        $response = $this->post(route('exchanges.confirm', $requestData));

        // Assert
        $response->assertStatus(302); // TODO: effetive unauthorized exception handling
        $this->assertEnrollmentsRemainUnchanged();
    }

    /** @test */
    public function unauthenticated_users_may_not_confirm_exchanges()
    {
        // Prepare
        $requestData = ['exchange_id' => $this->exchange->id];

        // Execute
        $response = $this->post(route('exchanges.confirm', $requestData));

        // Assert
        $response->assertRedirect(route('login'));
        $this->assertEnrollmentsRemainUnchanged();
    }

    protected function assertEnrollmentsRemainUnchanged()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualFromEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualToEnrollment->shift->id);
    }

    protected function assertEnrollmentsAreUpdated()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualToEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualFromEnrollment->shift->id);
    }
}
