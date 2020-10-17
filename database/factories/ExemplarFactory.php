<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Exemplar;
use Faker\Generator as Faker;

$factory->define(Exemplar::class, function (Faker $faker) {
    return [
        'codigo_barras' => $faker->unique()->randomNumber(),
        'edicao' => $faker->randomNumber(),
        'ano' => $faker->year(),
        'observacao' => $faker->text(),
        'obra' => factory(App\Obra::class)->create()->id
    ];
});
