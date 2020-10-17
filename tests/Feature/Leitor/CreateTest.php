<?php

namespace Tests\Feature\Leitor;

use App\Leitor;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createLeitor(User $user)
    {
        return $this->get(route('leitores.create',['user'=>$user]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createLeitor($this->user()->create())->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador_ou_atendente()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createLeitor($this->user()->create())->assertStatus(403);

        $this->actingAs($this->atendente()->create()->user()->first())
        ->createLeitor($this->user()->create())
        ->assertSessionHasNoErrors();

        $this->actingAs($this->coordenador()->create()->user()->first())
        ->createLeitor($this->user()->create())
        ->assertSessionHasNoErrors();
    }

   

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createLeitor($this->user()->create())
        ->assertViewIs('leitores.create');
    }

     /**
     * @test
     */
    public function leitor_deve_estar_previamente_cadastrado()
    {
        $user = $this->coordenador()->create()->user()->first();
        $usuario = new User();
        $usuario->id =-5;
        $this->actingAs($user)->createLeitor($usuario)
        ->assertNotFound();
    }

    /**
     * @test
     */
    public function user_deve_estar_ativo()
    {
        $user = $this->user()->create();
        $user->ativo = false;
        $user->save();

   
        $this->actingAs($this->coordenador()->create()->user()->first())
            ->createLeitor($user)
            ->assertSessionHasErrors();
    }
}
