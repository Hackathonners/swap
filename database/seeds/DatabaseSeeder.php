<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Only run the seeders registered here when we are in production,
        // otherwise keep it clean or it would somehow intefer with the
        // tests scenario preparation since data is stored in database.
        if (! App::environment('testing')) {
            $this->call(UsersTableSeeder::class);
        }
    }
}
