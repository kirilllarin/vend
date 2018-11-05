<?php

use App\Message;

$factory->define(Message::class, function(Faker\Generator $faker) {
    return [
        'message' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true),
    ];
});