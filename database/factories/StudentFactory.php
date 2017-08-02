<?php

use App\Judite\Models\User;
use App\Judite\Models\Student;

/*
|--------------------------------------------------------------------------
| Student Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Student::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(User::class)->create([
            'is_admin' => false,
            'verified' => true,
        ])->id,
        'student_number' => $faker->numerify('a#####'),
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->state(Student::class, 'unconfirmed', function (Faker\Generator $faker) {
    return [
        'user_id' => factory(User::class)->create([
            'is_admin' => false,
            'verified' => false,
        ])->id,
    ];
});
