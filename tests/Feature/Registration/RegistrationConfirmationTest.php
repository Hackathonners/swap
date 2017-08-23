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

    public function setUp()
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function authenticated_users_can_request_the_resend_of_the_confirmation_email()
    {
        // Prepare
        $student = factory(Student::class)->states('unconfirmed')->create();

        // Execute
        $this->actingAs($student->user);
        $this->post(route('register.resend_confirmation'));

        // Assert
        $user = $student->user;
        Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /** @test */
    public function a_confirmed_user_may_not_request_a_resend_of_the_confirmation_email()
    {
        // Prepare
        $student = factory(Student::class)->create();

        // Execute
        $this->actingAs($student->user);
        $this->post(route('register.resend_confirmation'));

        // Assert
        Mail::assertNotSent(RegistrationConfirmation::class);
    }

    /** @test */
    public function a_student_can_confirm_the_account()
    {
        // Prepare
        $student = factory(Student::class)->states('unconfirmed')->create();
        $requestData = ['token' => $student->user->verification_token];

        // Execute
        $this->actingAs($student->user);
        $this->get(route('register.confirm', $requestData));

        // Assert
        $student = $student->fresh();
        $this->assertTrue($student->user->verified);
        $this->assertNull($student->user->verification_token);
    }

    /** @test */
    public function an_account_may_not_be_confirmed_with_an_invalid_verification_token()
    {
        // Prepare
        $student = factory(Student::class)->states('unconfirmed')->create();
        $invalidRequestData = ['token' => 'invalid secret'];

        // Execute
        $this->actingAs($student->user);
        $this->get(route('register.confirm', $invalidRequestData));

        // Assert
        $student = $student->fresh();
        $this->assertFalse($student->user->verified);
    }
}
