<?php

use Faker\Generator as Faker;
use App\Model\User;

$factory->define(App\Model\User::class, function (Faker $faker) {
    return [
        'login_id' => $faker->unique()->randomElement($array = array ('ANT','BIRD','CAT', 'yano')),
        'password' => $faker->password,
        'classification' => $faker->unique()->randomElement($array = array ('COMPANY','FROFESSOR','STUDENT', 'AGENT')),
        'join_date' => $faker->dateTime(),
    ];
});
