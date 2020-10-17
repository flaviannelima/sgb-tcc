<?php

namespace Tests\Feature\TipoMaterial;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createTipoMaterial()
    {
        return $this->get(route('tiposmaterial.create'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createTipoMaterial()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createTipoMaterial()->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $this->withoutExceptionHandling();
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createTipoMaterial()->assertViewIs('tiposmaterial.create');
    }
}
