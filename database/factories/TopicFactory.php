<?php

use App\Topic;

$factory->define(Topic::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->catchPhrase
    ];
});