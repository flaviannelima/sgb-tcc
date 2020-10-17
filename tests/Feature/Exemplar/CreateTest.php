<?php

namespace Tests\Feature\Exemplar;

use App\Obra;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateTest extends TestCase
{
    private function createExemplar(Obra $obra)
    {
        return $this->get(route('exemplares.create',['obra'=>$obra]));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $this->createExemplar($this->obra()->create())->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $this->actingAs($user)->createExemplar($this->obra()->create())->assertStatus(403);
    }

   

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_create()
    {
        $user = $this->coordenador()->create()->user()->first();
        $this->actingAs($user)->createExemplar($this->obra()->create())->assertViewIs('exemplares.create');
    }

     /**
     * @test
     */
    public function obra_deve_estar_previamente_cadastrada()
    {
        $user = $this->coordenador()->create()->user()->first();
        $obra = new Obra();
        $obra->id =-5;
        $this->actingAs($user)->createExemplar($obra)
        ->assertNotFound();
    }

    /**
     * @test
     */
    public function obra_deve_estar_ativa()
    {
        $user = $this->coordenador()->create()->user()->first();
        $obra = $this->obra()->create();
        $obra->ativo = 0;
        $obra->save();
        $this->actingAs($user)->createExemplar($obra)
        ->assertSessionHasErrors();
    }

}
