<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Coordenador;
use Faker\Generator as Faker;

$factory->define(Coordenador::class, function (Faker $faker) {
   
    return [
        'user' => factory(App\User::class)->create()->id,
    ];
});
