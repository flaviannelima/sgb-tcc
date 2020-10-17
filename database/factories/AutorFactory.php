<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Autor;
use Faker\Generator as Faker;

$factory->define(Autor::class, function (Faker $faker) {
    return [
        'nome' => $faker->unique()->name
    ];
});
