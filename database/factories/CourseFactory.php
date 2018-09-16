<?php

use App\Judite\Models\Course;

/*
|--------------------------------------------------------------------------
| Course Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Course::class, function (Faker\Generator $faker) {
    $groupMax = $faker->numberBetween(0, 4);
    $groupMin = $faker->numberBetween(0, $groupMax);

    return [
        'code' => $faker->unique()->numerify('H###N#'),
        'year' => $faker->randomElement([1, 2, 3, 4, 5]),
        'name' => $faker->unique()->word(),
        'semester' => $faker->randomElement([1, 2]),
        'group_min' => $groupMin,
        'group_max' => $groupMax,
    ];
});
