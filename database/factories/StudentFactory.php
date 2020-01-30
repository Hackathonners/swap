<?php

use App\Judite\Models\User;
use Illuminate\Support\Str;
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
    $studentNumber = $faker->unique()->numerify('a#####');

    return [
        'user_id' => function () use ($studentNumber) {
            return factory(User::class)->create([
                'email' => "${studentNumber}@alunos.uminho.pt",
                'is_admin' => false,
                'verified' => true,
            ])->id;
        },
        'student_number' => $studentNumber,
    ];
});

$factory->state(Student::class, 'unverified', function (Faker\Generator $faker) {
    return [
        'user_id' => factory(User::class)->create([
            'is_admin' => false,
            'verified' => false,
            'verification_token' => Str::random(32),
        ])->id,
    ];
});
