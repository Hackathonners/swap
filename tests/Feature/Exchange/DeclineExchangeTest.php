<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Judite\Models\Exchange;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeclinedExchangeNotification;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeclineExchangeTest extends TestCase
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
    public function a_student_can_decline_a_proposed_exchange()
    {
        $response = $this->actingAs($this->toEnrollment->student->user)
            ->post(route('exchanges.decline', $this->exchange->id));

        $response->assertRedirect();
        $this->assertEquals(0, Exchange::count());
        $this->assertEnrollmentsRemainUnchanged();
        $notifiedUser = $this->exchange->fromStudent()->user;
        Mail::assertSent(DeclinedExchangeNotification::class, function ($mail) use ($notifiedUser) {
            return $mail->hasTo($notifiedUser->email);
        });
    }

    /** @test */
    public function a_student_may_not_decline_its_own_proposed_exchange()
    {
        $response = $this->actingAs($this->fromEnrollment->student->user)
            ->post(route('exchanges.decline', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(DeclinedExchangeNotification::class);
    }

    /** @test */
    public function a_student_may_not_decline_an_exchange_of_another_student()
    {
        $unauthorizedStudent = factory(Student::class)->create();

        $response = $this->actingAs($unauthorizedStudent->user)
            ->post(route('exchanges.decline', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(DeclinedExchangeNotification::class);
    }

    /** @test */
    public function admins_may_not_decline_exchanges()
    {
        $admin = factory(User::class)->states('admin')->create();

        $response = $this->actingAs($admin)
            ->post(route('exchanges.decline', $this->exchange->id));

        $response->assertStatus(404);
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(DeclinedExchangeNotification::class);
    }

    /** @test */
    public function unauthenticated_users_may_not_decline_exchanges()
    {
        $response = $this->post(route('exchanges.decline', $this->exchange->id));

        $response->assertRedirect(route('login'));
        $this->assertEnrollmentsRemainUnchanged();
        Mail::assertNotSent(DeclinedExchangeNotification::class);
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
}
