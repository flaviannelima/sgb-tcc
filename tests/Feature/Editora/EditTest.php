<?php

namespace Tests\Feature\Editora;

use App\Editora;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EditTest extends TestCase
{
    private function editEditora(Editora $editora)
    {
        return $this->get(route('editoras.edit',$editora));
    }
    /**
     * @test
     */
    public function usuario_deve_estar_autenticado()
    {
        $editora = $this->editora()->create();
        $this->editEditora($editora)->assertRedirect('/login');
    }

    /**
     * @test
     */
    public function usuario_deve_estar_cadastrado_como_coordenador()
    {
        $user = $this->user()->create();
        $editora = $this->editora()->create();
        $this->actingAs($user)->editEditora($editora)->assertStatus(403);
    }

    

    /**
     * @test
     */
    public function deve_redirecionar_para_pagina_edit_e_enviar_o_parametro_editora()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = $this->editora()->create();
        $this->actingAs($user)->editEditora($editora)->assertViewIs('editoras.edit')
        ->assertViewHas('editora',$editora);
    }

    /**
     * @test
     */
    public function editora_deve_existir_na_base_de_dados()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = new Editora();
        $editora->id = -1;
        $this->actingAs($user)->editEditora($editora)->assertNotFound();
    }

    /**
     * @test
     */
    public function editora_deve_estar_ativa()
    {
        $user = $this->coordenador()->create()->user()->first();
        $editora = $this->editora()->create();
        $editora->ativo = false;
        $editora->save();
        $this->actingAs($user)->editEditora($editora)->assertSessionHasErrors();
    }
}
