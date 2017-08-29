<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AccountVerificationController extends Controller
{
    use SendsConfirmationEmails;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can.student');
    }

    /**
     * Confirm an account.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($token)
    {
        $user = Auth::user();
        if ($user->verification_token === $token) {
            $user->verified = true;
            $user->verification_token = null;
            $user->save();
            flash('Your account is now confirmed.')->success();
        } else {
            flash('Invalid account confirmation.')->error();
        }

        return redirect($this->redirectTo);
    }

    /**
     * Send account confirmation email.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendEmail()
    {
        if (Auth::user()->verified) {
            flash('Your account is already confirmed.')->error();
        } else {
            $this->sendAccountConfirmationEmail(Auth::user());
            flash('A confirmation e-mail was sent. Please check your e-mail account.')->success();
        }

        return redirect($this->redirectTo);
    }
}
