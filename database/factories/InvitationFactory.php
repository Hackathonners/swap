<?php

use App\Judite\Models\Group;
use App\Judite\Models\Course;
use App\Judite\Models\Student;
use App\Judite\Models\Invitation;

/*
|--------------------------------------------------------------------------
| Invitation Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Invitation::class, function (Faker\Generator $faker) {
    return [
        'student_number' => function () {
            return factory(Student::class)->create()->student_number;
        },
        'course_id' => function () {
            return factory(Course::class)->create()->id;
        },
        'group_id' => function () {
            return factory(Group::class)->create()->id;
        },
    ];
});
