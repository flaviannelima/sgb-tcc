<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Leitor;
use Faker\Generator as Faker;

$factory->define(Leitor::class, function (Faker $faker) {
    $faker2 = \Faker\Factory::create('pt_BR');
    return [
        'cpf' => $faker2->cpf(),
        'data_nascimento' => $faker2->dateTimeThisCentury->format('Y-m-d'),
        'endereco' => $faker2->address(),
        'telefone_residencial' => $faker2->landlineNumber(true),
        'celular' => $faker2->cellphoneNumber(),
        'user' => factory(App\User::class)->create()->id,
    ];
});
