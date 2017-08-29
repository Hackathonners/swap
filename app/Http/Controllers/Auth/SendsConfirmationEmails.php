<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;

trait SendsConfirmationEmails
{
    /**
     * Send the confirmation e-mail to the given user.
     *
     * @param mixed $user
     */
    public function sendAccountConfirmationEmail($user)
    {
        Mail::to($user)->send(new RegistrationConfirmation($user));
    }
}
