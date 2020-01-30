<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegistrationTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function user_email_is_stored_as_lower_case()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'student_number' => 'pG12345',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $expectedEmail = 'pg12345@alunos.uminho.pt';
        $actualEmail = User::first()->email;
        $this->assertEquals(0, strcmp($expectedEmail, $actualEmail));
    }

    /** @test */
    public function a_confirmation_email_is_sent_after_registration()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'student_number' => 'pg12345',
            'password' => 'secret',
            'password_confirmation' => 'secret',
        ]);

        $email = 'pg12345@alunos.uminho.pt';
        $this->assertEquals(1, User::where('email', $email)->count());
        Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($email) {
            return $mail->hasTo($email);
        });
    }

    /** @test */
    public function a_user_may_not_register_without_a_student_email()
    {
        $response = $this->post(route('register'), [
            'name' => 'Marco Couto',
            'student_number' => '12345',
            'password' => '123456',
            'password_confirmation' => '123456',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('student_number');
        $this->assertEquals(0, User::count());
        Mail::assertNotSent(RegistrationConfirmation::class);
    }
}
