<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createUser()
    {
        return $this->get(route('users.create'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createUser()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador_ou_como_atendente()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createUser()->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {

        $this->actingAs($this->coordenador()->create()->user()->first())->createUser()
        ->assertViewIs('users.create');
        $this->actingAs($this->atendente()->create()->user()->first())->createUser()
        ->assertViewIs('users.create');
    }
}
