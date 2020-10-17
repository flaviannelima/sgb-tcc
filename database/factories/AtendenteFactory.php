<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Atendente;
use Faker\Generator as Faker;

$factory->define(Atendente::class, function (Faker $faker) {
    return [
        'user' => factory(App\User::class)->create()->id,
    ];
});
