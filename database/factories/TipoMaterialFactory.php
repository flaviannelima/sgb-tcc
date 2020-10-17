<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\TipoMaterial;
use Faker\Generator as Faker;

$factory->define(TipoMaterial::class, function (Faker $faker) {
    return [
        'descricao' => $faker->unique()->company()
    ];
});
