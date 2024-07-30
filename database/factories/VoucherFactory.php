<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Voucher;
use Faker\Generator as Faker;

$factory->define(Voucher::class, function (Faker $faker) {
    return [
        'code' => strtoupper($faker->lexify('??????')),
        'discount' => $faker->numberBetween(5, 50), // Example: 5% to 50%
        'expiry_date' => $faker->dateTimeBetween('now', '+1 year'),
        'photo' => null, // Handle photo upload separately
    ];
});
