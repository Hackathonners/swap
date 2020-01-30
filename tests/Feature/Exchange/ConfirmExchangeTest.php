<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConfirmedExchangeNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConfirmExchangeTest extends TestCase
{
    use DatabaseTransactions;

    protected $exchange;
    protected $fromEnrollment;
    protected $toEnrollment;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->enableExchangesPeriod();
        $this->exchange = factory(Exchange::class)->create();
        $this->fromEnrollment = $this->exchange->fromEnrollment;
        $this->toEnrollment = $this->exchange->toEnrollment;
    }

    /** @test */
    public function a_student_can_confirm_a_proposed_exchange()
    {
        $response = $this->actingAs($this->toEnrollment->student->user)
            ->post(route('exchanges.confirm', $this->exchange->id));

        $response->assertRedirect(route('dashboard'));
        $this->assertEnrollmentsChanged();
        $notifiedUser = $this->exchange->fromStudent()->user;
        Mail::assertSent(ConfirmedExchangeNotification::class, function ($mail) use ($notifiedUser) {
            return $mail->hasTo($notifiedUser->email);
        });
    }

    /** @test */
    public function an_exchange_is_performed_when_student_creates_a_matching_exchange_proposal()
    {
        $requestData = ['to_enrollment_id' => $this->fromEnrollment->id];

        $response = $this->actingAs($this->toEnrollment->student->user)
            ->post(route('exchanges.store', $this->toEnrollment->id), $requestData);

        $response->assertRedirect(route('dashboard'));
        $this->assertEnrollmentsChanged();
        $notifiedUser = $this->exchange->fromStudent()->user;
        Mail::assertSent(ConfirmedExchangeNotification::class, function ($mail) use ($notifiedUser) {
            return $mail->hasTo($notifiedUser->email);
        });
    }

    /** @test */
    public function a_student_may_not_confirm_its_own_proposed_exchange()
    {
        $response = $this->actingAs($this->fromEnrollment->student->user)
            ->post(route('exchanges.confirm', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(ConfirmedExchangeNotification::class);
    }

    /** @test */
    public function a_student_may_not_confirm_a_third_party_exchange()
    {
        $unauthorizedStudent = factory(Student::class)->create();

        $response = $this->actingAs($unauthorizedStudent->user)
            ->post(route('exchanges.confirm', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(ConfirmedExchangeNotification::class);
    }

    /** @test */
    public function admins_may_not_confirm_exchanges()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->post(route('exchanges.confirm', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(ConfirmedExchangeNotification::class);
    }

    /** @test */
    public function unauthenticated_users_may_not_confirm_exchanges()
    {
        $response = $this->post(route('exchanges.confirm', $this->exchange->id));

        $response->assertRedirect(route('login'));
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(ConfirmedExchangeNotification::class);
    }

    /*
     * Assert enrollments remain unchanged.
     */
    protected function assertEnrollmentsRemainUnchanged()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualFromEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualToEnrollment->shift->id);
    }

    /*
     * Assert enrollments changed.
     */
    protected function assertEnrollmentsChanged()
    {
        $actualFromEnrollment = $this->fromEnrollment->fresh();
        $actualToEnrollment = $this->toEnrollment->fresh();
        $this->assertEquals($this->fromEnrollment->shift->id, $actualToEnrollment->shift->id);
        $this->assertEquals($this->toEnrollment->shift->id, $actualFromEnrollment->shift->id);
    }
}
