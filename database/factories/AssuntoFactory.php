<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Assunto;
use Faker\Generator as Faker;

$factory->define(Assunto::class, function (Faker $faker) {
    return [
        "descricao" => $faker->unique()->company()
    ];
});
