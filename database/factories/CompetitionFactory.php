<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Competition;
use Faker\Generator as Faker;

$factory->define(Competition::class, function (Faker $faker) {
    return [
        'name' => $faker->words(3, true),
        'code' => (string) $faker->randomNumber(5),
    ];
});
