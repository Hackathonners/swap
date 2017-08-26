<?php

namespace App\Http\Controllers\Auth;

use App\Judite\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')
            ->except(['confirm', 'resendConfirmationEmail']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'student_number' => 'required|string|unique:students,student_number|student_number',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
     * @return \App\Judite\Models\User
     */
    protected function create(array $data)
    {
        $user = DB::transaction(function () use ($data) {
            $user = User::make([
                'name' => $data['name'],
                'email' => $data['student_number'].'@alunos.uminho.pt',
                'password' => bcrypt($data['password']),
            ]);
            $user->verification_token = str_random(32);
            $user->save();
            $user->student()->create(['student_number' => $data['student_number']]);

            return $user;
        });

        return $user;
    }

    /**
     * Confirm a student account.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm($token)
    {
        $user = Auth::user();

        if ($user->verification_token !== $token) {
            flash('Invalid account confirmation.')->error();

            return redirect($this->redirectTo);
        }

        $user->verified = true;
        $user->verification_token = null;
        $user->save();

        flash('Your account is now confirmed.')->success();

        return redirect($this->redirectTo);
    }

    /**
     * Resend confirmation email of a student account.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resendConfirmationEmail()
    {
        $user = Auth::user();
        if ($user->verified) {
            flash('Your account is already confirmed.')->error();

            return redirect($this->redirectTo);
        }

        $this->sendConfirmationEmail(Auth::user());
        flash('A new confirmation e-mail has been sent. Please check your e-mail account.')->success();

        return redirect($this->redirectTo);
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     */
    protected function registered(Request $request, $user)
    {
        $this->sendConfirmationEmail($user);
    }

    /**
     * Send the confirmation e-mail to the given user.
     *
     * @param mixed $user
     */
    protected function sendConfirmationEmail($user)
    {
        Mail::to($user)->send(new RegistrationConfirmation($user));
    }
}
