<?php

namespace App\Console\Commands;

use App\Judite\Models\Student;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationConfirmation;

class ResendConfirmationEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:confirmation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend confirmation e-mail';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::transaction(function () {
            $students = Student::with('user')->get();
            $students->each(function ($student) {
                $user = $student->user;
                if ($user->verified) {
                    return;
                }

                Mail::to($user)->send(new RegistrationConfirmation($user));
            });
        });
    }
}
