<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Judite\Models\Student::class, function (Faker\Generator $faker) {
    return [
        'user_id' => factory(App\Judite\Models\User::class)->create(['is_admin' => false])->id,
        'student_number' => $faker->numerify('a#####'),
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Judite\Models\Course::class, function (Faker\Generator $faker) {
    return [
        'year' => $faker->randomElement($array = [1, 2, 3, 4, 5]),
        'name' => $faker->word(),
        'semester' => $faker->randomElement($array = [1, 2]),
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Judite\Models\Enrollment::class, function (Faker\Generator $faker) {
    return [
        'student_id' => factory(App\Judite\Models\Student::class)->create()->id,
        'course_id' => factory(App\Judite\Models\Course::class)->create()->id,
    ];
});

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Judite\Models\Shift::class, function (Faker\Generator $faker) {
    return [
        'course_id' => factory(App\Judite\Models\Course::class)->create()->id,
        'tag' => $faker->word(),
    ];
});
