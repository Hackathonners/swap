<?php

namespace Tests\Feature\Registration;

use Tests\TestCase;
use App\Judite\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationConfirmationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function authenticated_users_can_request_the_resend_of_the_confirmation_email()
    {
        $student = factory(Student::class)->states('unverified')->create();

        $this->actingAs($student->user)
            ->post(route('register.resend_confirmation'));

        $user = $student->user;
        Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function a_confirmed_user_may_not_request_a_resend_of_the_confirmation_email()
    {
        $student = factory(Student::class)->create();

        $this->actingAs($student->user)
            ->post(route('register.resend_confirmation'));

        Mail::assertNotSent(RegistrationConfirmation::class);
    }

    /** @test */
    public function a_student_can_confirm_the_account()
    {
        $student = factory(Student::class)->states('unverified')->create();

        $this->actingAs($student->user)
            ->get(route('register.confirm', ['token' => $student->user->verification_token]));

        $student->refresh();
        $this->assertTrue($student->user->verified);
        $this->assertNull($student->user->verification_token);
    }

    /** @test */
    public function an_account_may_not_be_confirmed_with_an_invalid_verification_token()
    {
        $student = factory(Student::class)->states('unverified')->create();

        $this->actingAs($student->user)
            ->get(route('register.confirm', ['token' => 'invalid secret']));

        $student->refresh();
        $this->assertFalse($student->user->verified);
    }
}
