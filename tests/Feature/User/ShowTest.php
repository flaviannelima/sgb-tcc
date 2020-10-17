<?php

namespace Tests\Feature\User;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ShowTest extends TestCase
{
    private function showUser(User $user)
    {
        return $this->get(route('users.show',$user));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $user = $this->user()->create();
        $this->showUser($user)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador_ou_como_atendente()
    {
        $user = $this->user()->create();
        $user2 = $this->user()->create();
        $this->actingAs($user)->showUser($user2)->assertStatus(403);

        $user = $this->atendente()->create()->user()->first();
        $this->actingAs($user)->showUser($user2)->assertViewIs('users.show');

        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->showUser($user2)->assertViewIs('users.show');
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_show_e_enviar_o_parametro_user()
    {
        $user = $this->coordenador()->create()->user()->first();
        $user2 = $this->user()->create();
        $this->actingAs($user)->showUser($user2)->assertViewIs('users.show')
        ->assertViewHas('user',$user2);
    }

    /**
     * @test
     */
    public function user_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $user2 = new User();
        $user2->id = -1;
        $this->actingAs($user)->showUser($user2)->assertNotFound();
    }
}
