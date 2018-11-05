<?php

use App\Event;
use Carbon\Carbon;

$factory->define(Event::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->catchPhrase,
        'start' => Carbon::now()->addDays(7),
        'end' => Carbon::now()->addDays(8),
        'location' => $faker->address,
        'description' => $faker->catchPhrase
    ];
});