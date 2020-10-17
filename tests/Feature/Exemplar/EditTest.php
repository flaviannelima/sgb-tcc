<?php

namespace Tests\Feature\Exemplar;

use App\Exemplar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function editExemplar(Exemplar $exemplar)
    {
        return $this->get(route('exemplares.edit',$exemplar));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $exemplar = $this->exemplar()->create();
        $this->editExemplar($exemplar)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $exemplar = $this->exemplar()->create();
        $this->actingAs($user)->editExemplar($exemplar)->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_exemplar()
    {
        $user = $this->coordenador()->create()->user()->first();
        $exemplar = $this->exemplar()->create();
        $this->actingAs($user)->editExemplar($exemplar)->assertViewIs('exemplares.edit')
        ->assertViewHas('exemplar',$exemplar);
    }

    /**
     * @test
     */
    public function exemplar_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $exemplar = new Exemplar();
        $exemplar->id = -1;
        $this->actingAs($user)->editExemplar($exemplar)->assertNotFound();
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
        $exemplar = $this->exemplar()->setObra($obra->id)->create();
        $this->actingAs($user)->editExemplar($exemplar)
        ->assertSessionHasErrors();
    }

    /**
     * @test
     */
    public function exemplar_deve_estar_ativo()
    {
        $user = $this->coordenador()->create()->user()->first();
        $exemplar = $this->exemplar()->create();
        $exemplar->ativo = 0;
        $exemplar->save();
        $this->actingAs($user)->editExemplar($exemplar)
        ->assertSessionHasErrors();
    }
}
