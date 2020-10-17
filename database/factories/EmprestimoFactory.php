<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Emprestimo;
use Faker\Generator as Faker;

$factory->define(Emprestimo::class, function (Faker $faker) {
    return [
        'exemplar' => factory(App\Exemplar::class)->create()->codigo_barras,
        'leitor' => factory(App\Leitor::class)->create()->id,
    ];
});
