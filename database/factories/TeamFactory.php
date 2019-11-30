<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Team;
use Faker\Generator as Faker;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'name'       => $faker->words(3, true),
        'short_name' => $faker->word,
        'tla'        => $faker->word,
        'address'    => $faker->address,
        'phone'      => $faker->phoneNumber,
        'website'    => $faker->url,
        'email'      => $faker->email,
        'founded'    => $faker->date('Y'),
    ];
});
