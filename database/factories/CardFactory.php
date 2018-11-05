<?php

use App\Card;

$factory->define(Card::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->catchPhrase,
        'description' => $faker->catchPhrase
    ];
});