<?php

use App\Account;
use Carbon\Carbon;

$factory->define(Account::class, function(Faker\Generator $faker) {
    return [
        'title' => $faker->catchPhrase,
        'package' => 0,
        'expire_at' => Carbon::now()->addDays(7)
    ];
});