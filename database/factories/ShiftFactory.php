<?php

use App\Judite\Models\Shift;
use App\Judite\Models\Course;

/*
|--------------------------------------------------------------------------
| Shift Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Shift::class, function (Faker\Generator $faker) {
    return [
        'tag' => $faker->numerify('TP#'),
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
    ];
});
