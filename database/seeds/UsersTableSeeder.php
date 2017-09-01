<?php

use App\Judite\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => env('ADMIN_EMAIL', 'contact@hackathonners.org'),
            'password' => bcrypt(env('ADMIN_PASSWORD', '123456')),
            'is_admin' => true,
        ]);
    }
}
