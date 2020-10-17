<?php

namespace Tests\Feature\Editora;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createEditora()
    {
        return $this->get(route('editoras.create'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createEditora()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createEditora()->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createEditora()->assertViewIs('editoras.create');
    }
}
