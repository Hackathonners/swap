<?php

/*
|--------------------------------------------------------------------------
| User Factory
|--------------------------------------------------------------------------
|
| Here you may define all factory states for the User model.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Judite\Models\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->state(App\Judite\Models\User::class, 'admin', function (Faker\Generator $faker) {
    return [
        'is_admin' => true,
    ];
});
