<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
     *
     * @return void
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
        //
    }
}
