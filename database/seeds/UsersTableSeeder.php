<?php

use App\Judite\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'contact@hackathonners.org',
            'password' => bcrypt('123456'),
            'is_admin' => true,
        ]);
    }
}
