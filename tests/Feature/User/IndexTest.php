<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IndexTest extends TestCase
{
    private function indexUser()
    {
        return $this->get(route('users.index'));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->indexUser()->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador_ou_atendente()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->indexUser()->assertStatus(403);

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->indexUser()->assertViewIs('users.index');

        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)->indexUser()->assertViewIs('users.index');
    }

  

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_index()
    {
        $user = $this->coordenador()->create()->user()->first();
        $rota = $this->actingAs($user)->indexUser();
        $users = User::orderBy('name')->paginate(9);
        $rota->assertViewIs('users.index')
        ->assertViewHas('users',$users);
    }
}
