<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Judite\Models\Student;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ResendConfirmationEmailTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function resend_confirmation_email_to_unconfirmed_users()
    {
        Mail::fake();
        $students = factory(Student::class, 5)->create();
        $unverifiedStudents = factory(Student::class, 3)->states('unverified')->create();

        $this->artisan('emails:confirmation');

        Mail::assertSent(RegistrationConfirmation::class, 3);
        $unverifiedStudents->each(function ($student) {
            $user = $student->user;
            Mail::assertSent(RegistrationConfirmation::class, function ($mail) use ($user) {
                return $mail->hasTo($user->email) && $mail->user->email === $user->email;
            });
        });
    }
}
