<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BuscaTest extends TestCase
{
    private function buscaUser(User $user)
    {
        return $this->post(route('users.busca'),[$user->toArray()]);
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $user = $this->user()->create();
        $this->buscaUser($user)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador_ou_como_atendente()
    {
        $user = $this->user()->create();
        $this->actingAs($this->user()->create())->buscaUser($user)->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_busca()
    {

        $user = $this->user()->create();
        $rota = $this->actingAs($this->coordenador()->create()->user()->first())
        ->buscaUser($user);
        $users = User::orderBy('name')->paginate(9);
        $rota->assertViewIs('users.index')
        ->assertViewHas('users',$users);

        $user = $this->user()->create();
        $rota = $this->actingAs($this->atendente()->create()->user()->first())
        ->buscaUser($user);
        $users = User::orderBy('name')->paginate(9);
        $rota->assertViewIs('users.index')
        ->assertViewHas('users',$users);
    }
}
