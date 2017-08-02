<?php

namespace Tests\Feature\Registration;

use Mail;
use Tests\TestCase;
use App\Judite\Models\User;
use App\Judite\Models\Student;
use App\Mail\RegistrationConfirmation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationConfirmationTest extends TestCase
{
    use DatabaseTransactions;

    public function testSendConfirmatioEmailOnRegistration()
    {
        // Prepare
        Mail::fake();
        $requestData = [
            'name' => 'John Doe',
            'email' => 'pg12345@alunos.uminho.pt',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        // Execute
        $response = $this->post('/register', $requestData);

        // Assert
        Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($requestData) {
            return $mail->hasTo($requestData['email']);
        });
    }

    public function testSendResendConfirmationEmail()
    {
        // Prepare
        Mail::fake();
        $student = factory(Student::class)->states('unconfirmed')->create();

        // Execute
        $response = $this->actingAs($student->user)
                         ->post(route('register.resend_confirmation'));

        // Assert
        $user = $student->user;
        Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function testDoNotSendResendConfirmationEmailToConfirmedUser()
    {
        // Prepare
        Mail::fake();
        $student = factory(Student::class)->create();

        // Execute
        $response = $this->actingAs($student->user)
                         ->post(route('register.resend_confirmation'));

        // Assert
        Mail::assertNotSent(RegistrationConfirmation::class);
    }

    public function testConfirmStudentAccount()
    {
        // Prepare
        $user = factory(User::class)->create([
            'verified' => false,
            'verification_token' => 'secret',
        ]);
        factory(Student::class)->create(['user_id' => $user->id]);
        $requestData = ['token' => 'secret'];

        // Execute
        $response = $this->actingAs($user)
                         ->get(route('register.confirm', $requestData));

        // Assert
        $user->fresh();
        $this->assertTrue($user->verified);
        $this->assertNull($user->verification_token);
    }

    public function testDoNotConfirmStudentAccountOnInvalidToken()
    {
        // Prepare
        $user = factory(User::class)->create([
            'verified' => false,
            'verification_token' => 'secret',
        ]);
        factory(Student::class)->create(['user_id' => $user->id]);
        $invalidRequestData = ['token' => 'invalid_secret'];

        // Execute
        $response = $this->actingAs($user)
                         ->get(route('register.confirm', $invalidRequestData));

        // Assert
        $user->fresh();
        $this->assertFalse($user->verified);
    }
}
