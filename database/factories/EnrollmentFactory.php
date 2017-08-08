<?php

use App\Judite\Models\Shift;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Enrollment;

/*
|--------------------------------------------------------------------------
| Enrollment Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Enrollment::class, function (Faker\Generator $faker) {
    return [
        'student_id' => function () {
            return factory(Student::class)->create()->id;
        },
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
        'shift_id' => function () {
            return factory(Shift::class)->create()->id;
        },
    ];
});
