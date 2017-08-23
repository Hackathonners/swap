<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::transaction(function () {
            // Only run the seeders registered here when we are in production,
            // otherwise keep it clean or it would somehow intefer with the
            // tests scenario preparation since data is stored in database.
            if (! App::environment('testing')) {
                $this->call(UsersTableSeeder::class);
                $this->call(CoursesTableSeeder::class);
                $this->call(SettingsTableSeeder::class);
            }
        });
    }
}
