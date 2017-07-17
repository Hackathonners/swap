<?php

use App\Judite\Models\Course;
use App\Judite\Models\Exchange;
use App\Judite\Models\Enrollment;

/*
|--------------------------------------------------------------------------
| Exchange Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Exchange::class, function (Faker\Generator $faker) {
    $course = factory(Course::class)->create();

    return [
        'from_enrollment_id' => factory(Enrollment::class)->create(['course_id' => $course->id])->id,
        'to_enrollment_id' => factory(Enrollment::class)->create(['course_id' => $course->id])->id,
    ];
});
