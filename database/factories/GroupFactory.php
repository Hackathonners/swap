<?php

use App\Judite\Models\Group;
use Faker\Generator as Faker;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(Group::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
    ];
});
