<?php

namespace App\Http\Controllers\Auth;

use App\Judite\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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

    use RegistersUsers, SendsConfirmationEmails;

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
        $this->middleware('guest');
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
        if (! is_null($data['student_number'])) {
            $data['student_number'] = strtolower($data['student_number']);
        }

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
            $data['student_number'] = strtolower($data['student_number']);
            $user = User::make([
                'name' => $data['name'],
                'email' => $data['student_number'].'@'.config('app.mail_domain'),
                'password' => bcrypt($data['password']),
            ]);
            $user->verification_token = Str::random(32);
            $user->save();
            $user->student()->create(['student_number' => $data['student_number']]);

            return $user;
        });

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed                    $user
     */
    protected function registered(Request $request, $user)
    {
        $this->sendAccountConfirmationEmail($user);
    }
}
