<?php

namespace App\Console\Commands;

use App\Judite\Models\Shift;
use App\Judite\Models\Exchange;
use Illuminate\Console\Command;
use App\Judite\Models\Enrollment;
use Illuminate\Support\Facades\DB;
use App\Judite\Contracts\Registry\ExchangeRegistry;

class ResetEnrollments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:enrollments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete existing enrollments and all data associated to them.';

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
            // Delete exchange registry.
            resolve(ExchangeRegistry::class)->truncate();

            // Delete exchanges.
            Exchange::query()->delete();

            // Delete enrollments
            Enrollment::query()->delete();

            // Delete all shifts.
            Shift::query()->delete();
        });
    }
}
