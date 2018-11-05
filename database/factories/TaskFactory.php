<?php

use App\Task;

$factory->define(Task::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->sentence,
    ];
});