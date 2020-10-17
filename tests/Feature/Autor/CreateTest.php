<?php

namespace Tests\Feature\Autor;

use App\Autor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createAutor()
    {
        return $this->get(route('autores.create'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createAutor()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createAutor()->assertStatus(403);
    }


    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createAutor()->assertViewIs('autores.create');
    }
}
