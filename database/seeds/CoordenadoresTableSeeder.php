<?php

use App\Coordenador;
use Illuminate\Database\Seeder;

class CoordenadoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Coordenador::create([
            'user' => 1
        ]);
    }
}
