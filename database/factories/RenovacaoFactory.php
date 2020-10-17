<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Renovacao;
use Faker\Generator as Faker;

$factory->define(Renovacao::class, function (Faker $faker) {
    $exemplar = factory(App\Exemplar::class)->create();
    $atributo['exemplar'] = $exemplar->id;
    $atributo['usuario_emprestou'] = factory(App\User::class)->create()->id;
    $atributo['data_prevista_devolucao'] = date('Y-m-d');
    return [
        'emprestimo' => factory(App\Emprestimo::class)->create($atributo)->id
    ];
});
