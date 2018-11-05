<?php

use App\Column;
use App\Project;
use App\User;

$order = 0;
$factory->define(Column::class, function (Faker\Generator $faker) use($order) {
    return [
        'title' => $faker->word,
        'order' => $order++
    ];
});

$factory->define(Project::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->catchPhrase,
        'description' => $faker->paragraph($nbSentences = 3, $variableNbSentences = true)
    ];
});