<?php

namespace Tests\Feature\Assunto;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createAssunto()
    {
        return $this->get(route('assuntos.create'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createAssunto()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createAssunto()->assertStatus(403);
    }

    
    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createAssunto()->assertViewIs('assuntos.create');
    }
}
