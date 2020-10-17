<?php

namespace Tests\Feature\Obra;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createObra()
    {
        return $this->get(route('obras.create'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createObra()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createObra()->assertStatus(403);
    }



    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createObra()->assertViewIs('obras.create');
    }
}
