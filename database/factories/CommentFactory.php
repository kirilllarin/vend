<?php

use App\Comment;

$factory->define(Comment::class, function(Faker\Generator $faker) {
    return [
        'content' => $faker->catchPhrase
    ];
});