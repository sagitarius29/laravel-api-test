<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entities\Player;
use Faker\Generator as Faker;

$factory->define(Player::class, function (Faker $faker, $attr) {
    $team_id = $attr['team_id'] ??
        (optional(\App\Entities\Team::inRandomOrder()->first())->id ?? factory(\App\Entities\Team::class)->create()->id);
    return [
        'team_id'      => $team_id,
        'name'         => $faker->name,
        'position'     => $faker->word,
        'shirt_number' => $faker->numberBetween(1, 99),
    ];
});
