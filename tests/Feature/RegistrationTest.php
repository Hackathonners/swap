<?php

namespace Tests\Feature;

use Mail;
use Tests\TestCase;
use App\Judite\Models\User;
use App\Mail\RegistrationConfirmation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp()
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function a_confirmation_email_is_sent_after_registration()
    {
        // Prepare
        $requestData = [
            'name' => 'John Doe',
            'email' => 'pg12345',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ];

        // Execute
        $response = $this->post('/register', $requestData);

        // Assert
        $email = $requestData['email'].'@alunos.uminho.pt';
        Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
        $this->assertEquals(1, User::where('email', $email)->count());
    }

    /** @test */
    public function a_user_may_not_register_without_a_student_email()
    {
        // Prepare
        $requestData = [
            'name' => 'Marco Couto',
            'email' => 'a12345@mail.pt',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];

        // Execute
        $response = $this->post(route('register'), $requestData);

        // Assert
        $this->assertEquals(0, User::count());
    }
}
