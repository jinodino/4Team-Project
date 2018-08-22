<?php

use Faker\Generator as Faker;
use App\Model\country_code;


function generateRandomString($length=10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$factory->define(App\Model\country_code::class, function (Faker $faker) {
   
    return [
        'country_code' => generateRandomString(2),
        'country_num' => $faker->unique()->numberBetween($min = 100, $max = 999),
        'continent' => generateRandomString(2),
        'country_eng' => $faker->unique()->country,
        'country_kana' => $faker->unique()->country,
        
    ];
});
