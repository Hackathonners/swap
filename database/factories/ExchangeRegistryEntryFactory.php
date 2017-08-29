<?php

use App\Judite\Models\Exchange;
use App\Judite\Models\ExchangeRegistryEntry;

/*
|--------------------------------------------------------------------------
| ExchangeRegistryEntry Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(ExchangeRegistryEntry::class, function (Faker\Generator $faker) {
    $exchange = factory(Exchange::class)->create();

    return [
        'from_shift_id' => $exchange->fromShift(),
        'to_shift_id' => $exchange->toShift(),
        'from_student_id' => $exchange->fromStudent(),
        'to_student_id' => $exchange->toStudent(),
    ];
});
