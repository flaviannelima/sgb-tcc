<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Obra;
use Faker\Generator as Faker;
use Illuminate\Support\Str;
$factory->define(Obra::class, function (Faker $faker) {
    
    return [
        'tipo_material' => factory(App\TipoMaterial::class)->create()->id,
        'categoria' => factory(App\Categoria::class)->create()->id,
        'titulo' => $faker->title(),
        'editora' => factory(App\Editora::class)->create()->id,
        'volume' => $faker->randomDigit,
        'localizacao' => str_pad(random_int(0, 99), 2, "0", STR_PAD_LEFT) . "." . str_pad(random_int(0, 99), 2, "0", STR_PAD_LEFT) . "." .
        str_pad(random_int(0, 99), 2, "0", STR_PAD_LEFT).' AA AA',
        'observacao' => $faker->text(),
        //'autores' => factory(App\Autor::class,3)->create(),
        //'assuntos' => factory(App\Assunto::class,3)->create(),

    ];
});
