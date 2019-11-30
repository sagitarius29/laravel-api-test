<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Player;
use Faker\Generator as Faker;

$factory->define(Player::class, function (Faker $faker) {
    return [
        'name'         => $faker->name,
        'position'     => $faker->word,
        'shirt_number' => $faker->numberBetween(1, 99),
    ];
});
