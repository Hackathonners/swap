<?php

use App\Judite\Models\Course;
use App\Judite\Models\DirectExchange;
use App\Judite\Models\Enrollment;

/*
|--------------------------------------------------------------------------
| DirectExchange Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(DirectExchange::class, function (Faker\Generator $faker) {
    $course = factory(Course::class)->create();

    return [
        'from_enrollment_id' => factory(Enrollment::class)->create(['course_id' => $course->id])->id,
        'to_enrollment_id' => factory(Enrollment::class)->create(['course_id' => $course->id])->id,
    ];
});
