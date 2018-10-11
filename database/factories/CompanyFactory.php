<?php

use Faker\Generator as Faker;

$factory->define(App\Company::class, function (Faker $faker) {
    return [
        'company_name' => $faker->text(20),
        'company_address' => $faker->text(50),
        'federation_id' => 1,
        'typology_id' => 1,
        'sport_id' => 1,
        'registrant_id' => 1,
        'user_email' => str_random(10)."@gmail.com"
    ];
});